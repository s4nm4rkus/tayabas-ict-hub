<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

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

        return view('hr.leave.show', compact('leave'));
    }

    public function approve(Request $request, int $id)
    {
        $request->validate([
            'hr_as_of'  => 'nullable|string',
            'vl_earned' => 'nullable|string',
            'vl_less'   => 'nullable|string',
            'vl_balance' => 'nullable|string',
            'sl_earned' => 'nullable|string',
            'sl_less'   => 'nullable|string',
            'sl_balance' => 'nullable|string',

              // ── Section 7C — HR now fills these ──────────────────────────
            'asds_days_with_pay'    => 'nullable|string',
            'asds_days_without_pay' => 'nullable|string',
            'asds_others'           => 'nullable|string|max:100',
        ]);

        $leave = Leave::findOrFail($id);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Build full name for e-signature watermark
        $employee = \App\Models\Employee::where('user_id', $user->id)->first();
        $hrName   = $employee
            ? strtoupper($employee->full_name)
            : strtoupper($user->username);

        $leave->update([
            'leave_status'  => 'Pending AO',   // ← now routes to AO instead of Approved
            'approve_by'    => $user->id,
            'hr_esign_name' => $hrName,
              'hr_esign_path' => $user->e_signature,

            // Leave credits (Section 7A)
            'hr_as_of'   => $request->hr_as_of,
            'vl_earned'  => $request->vl_earned,
            'vl_less'    => $request->vl_less,
            'vl_balance' => $request->vl_balance,
            'sl_earned'  => $request->sl_earned,
            'sl_less'    => $request->sl_less,
            'sl_balance' => $request->sl_balance,

             // ── Section 7C: Approved For — HR fills, stored in asds columns
            // (Option A: reuse existing columns, no migration needed)
            'asds_days_with_pay'    => $request->asds_days_with_pay,
            'asds_days_without_pay' => $request->asds_days_without_pay,
            'asds_others'           => $request->asds_others,

        ]);

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
