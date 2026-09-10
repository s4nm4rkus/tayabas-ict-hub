<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\LeaveBalance;
use App\Models\LeaveCreditTransaction;
use App\Services\LeaveCreditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use RuntimeException;

class HRLeaveController extends Controller
{
    public function index()
    {
        $leaves = Leave::with('employee')
            ->where('leave_status', 'Pending HR')
            ->orderBy('date_applied', 'asc')
            ->get();

        $processed = Leave::with('employee')
            ->whereNotIn('leave_status', ['Pending HR', 'Pending Head'])
            ->orderBy('updated_at', 'desc')
            ->take(20)
            ->get();

        return view('hr.leave.index', compact('leaves', 'processed'));
    }

    public function show(int $id)
    {
        $leave = Leave::with(['employee', 'deptHead', 'approvedBy', 'aoApprover', 'asdsApprover'])
            ->findOrFail($id);

        // Read-only reference for HR — does NOT affect what gets saved.
        // Leave->user_id resolves to tbl_employee_info.id, matching how
        // LeaveBalance.employee_id is keyed.
        $leaveBalance = LeaveBalance::where('employee_id', $leave->user_id)->first();

        // Preview values only — nothing here is saved until HR submits the
        // form, and HR can edit any of these before submitting.
        $prefill = [
            'hr_as_of'   => null,
            'vl_earned'  => null,
            'sl_earned'  => null,
            'vl_less'    => null,
            'sl_less'    => null,
            'vl_balance' => null,
            'sl_balance' => null,
        ];

        if ($leaveBalance) {
            $days = (float) $leave->total_days;
            $type = trim($leave->leave_types ?? $leave->leavetype ?? '');

            $prefill['hr_as_of']   = $leaveBalance->as_of_date?->format('M d, Y');
            $prefill['vl_balance'] = $leaveBalance->vl_balance;
            $prefill['sl_balance'] = $leaveBalance->sl_balance;

            // If the leave type isn't exactly "Vacation Leave" or "Sick
            // Leave" (e.g. a combined type, or something unrecognized),
            // vl_less/sl_less stay null rather than guessed — HR fills
            // those in manually, same as before this feature existed.
            if ($type === 'Vacation Leave') {
                $prefill['vl_less']    = $days;
                $prefill['vl_balance'] = $leaveBalance->vl_balance - $days;
            } elseif ($type === 'Sick Leave') {
                $prefill['sl_less']    = $days;
                $prefill['sl_balance'] = $leaveBalance->sl_balance - $days;
            }

            // ── "Earned" — sum of MONTHLY_EARNING transactions since the
            // last leave deduction (or since opening balance, if this is
            // the employee's first-ever leave application). Stays null
            // (not a blank-zero) if monthly computation has never run for
            // this employee at all — an unknown is not the same thing as
            // a confirmed zero.
            $anchor = LeaveCreditTransaction::where('employee_id', $leave->user_id)
                ->where('transaction_type', LeaveCreditTransaction::TYPE_LEAVE_DEDUCTION)
                ->orderByDesc('effective_date')
                ->orderByDesc('id')
                ->first()
                ?? LeaveCreditTransaction::where('employee_id', $leave->user_id)
                    ->where('transaction_type', LeaveCreditTransaction::TYPE_OPENING_BALANCE)
                    ->first();

            $hasAnyMonthlyEarning = LeaveCreditTransaction::where('employee_id', $leave->user_id)
                ->where('transaction_type', LeaveCreditTransaction::TYPE_MONTHLY_EARNING)
                ->exists();

            if ($anchor && $hasAnyMonthlyEarning) {
                $earnedSince = LeaveCreditTransaction::where('employee_id', $leave->user_id)
                    ->where('transaction_type', LeaveCreditTransaction::TYPE_MONTHLY_EARNING)
                    ->where('effective_date', '>', $anchor->effective_date)
                    ->selectRaw('SUM(vl_amount) as vl_sum, SUM(sl_amount) as sl_sum')
                    ->first();

                $prefill['vl_earned'] = round((float) $earnedSince->vl_sum, 3);
                $prefill['sl_earned'] = round((float) $earnedSince->sl_sum, 3);
            }
        }

        return view('hr.leave.show', compact('leave', 'leaveBalance', 'prefill'));
    }

    public function approve(Request $request, int $id, LeaveCreditService $leaveCreditService)
    {
        $request->validate([
            'hr_as_of'  => 'nullable|string',
            'vl_earned' => 'nullable|string',
            'vl_less'   => 'nullable|string',
            'vl_balance' => 'nullable|string',
            'sl_earned' => 'nullable|string',
            'sl_less'   => 'nullable|string',
            'sl_balance' => 'nullable|string',
            'asds_days_with_pay'    => 'nullable|string',
            'asds_days_without_pay' => 'nullable|string',
            'asds_others'           => 'nullable|string|max:100',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        try {
            DB::transaction(function () use ($request, $id, $user, $leaveCreditService) {

                // Lock the row so a double-submit can't approve (and deduct) twice.
                $leave = Leave::where('id', $id)->lockForUpdate()->firstOrFail();

                if ($leave->leave_status !== 'Pending HR') {
                    throw new RuntimeException('This leave is no longer pending HR review — it may have already been processed.');
                }

                $employee = \App\Models\Employee::where('user_id', $user->id)->first();
                $hrName   = $employee ? strtoupper($employee->full_name) : strtoupper($user->username);

                $leave->update([
                    'leave_status'  => 'Pending AO',
                    'approve_by'    => $user->id,
                    'hr_esign_name' => $hrName,
                    'hr_esign_path' => $user->e_signature,

                    'hr_as_of'   => $request->hr_as_of,
                    'vl_earned'  => $request->vl_earned,
                    'vl_less'    => $request->vl_less,
                    'vl_balance' => $request->vl_balance,
                    'sl_earned'  => $request->sl_earned,
                    'sl_less'    => $request->sl_less,
                    'sl_balance' => $request->sl_balance,

                    'asds_days_with_pay'    => $request->asds_days_with_pay,
                    'asds_days_without_pay' => $request->asds_days_without_pay,
                    'asds_others'           => $request->asds_others,
                ]);

                // ── Ledger deduction — only for recognized single types ──
                $type = trim($leave->leave_types ?? $leave->leavetype ?? '');
                $leaveTypeCode = match ($type) {
                    'Vacation Leave' => 'VL',
                    'Sick Leave'     => 'SL',
                    default          => null,
                };

                if ($leaveTypeCode !== null) {
                    $days = (float) $leave->total_days;

                    if ($days > 0) {
                        $leaveCreditService->deductForLeave(
                            employeeId: $leave->user_id,
                            leaveType: $leaveTypeCode,
                            days: $days,
                            effectiveDate: now()->toDateString(),
                            referenceId: $leave->id,
                            createdBy: $user->id,
                            approvedBy: $user->id,
                        );
                    }
                }
            });
        } catch (RuntimeException $e) {
            return back()->withErrors(['balance' => $e->getMessage()])->withInput();
        }

        return redirect()->route('hr.leave.index')
            ->with('success', 'Leave approved and forwarded to Administrative Officer.');
    }

    public function decline(Request $request, int $id)
    {
        $request->validate([
            'remarks' => 'required|string|max:100',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $leave = Leave::findOrFail($id);
        $leave->update([
            'leave_status' => 'Declined',
            'remarks'      => $request->remarks,
            'approve_by'   => $user->id,
        ]);

        return redirect()->route('hr.leave.index')
            ->with('success', 'Leave declined.');
    }

    public function print(int $id)
    {
        $leave = Leave::with([
            'employee', 'employee.user',
            'deptHead', 'approvedBy',
            'aoApprover', 'asdsApprover',
        ])->findOrFail($id);

        return view('employee.leave.form6_print', compact('leave'));
    }

    public function pdf(int $id)
    {
        $leave = Leave::with([
            'employee', 'employee.user',
            'deptHead', 'approvedBy',
            'aoApprover', 'asdsApprover',
        ])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.form6_pdf', compact('leave'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => true,
                'defaultFont'          => 'Arial',
            ]);

        $filename = 'Form6_'
            . str_replace(' ', '_', $leave->fullname)
            . '_' . now()->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }
}
