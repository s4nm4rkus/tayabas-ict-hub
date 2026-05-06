<?php

namespace App\Http\Controllers\ASDS;

use App\Http\Controllers\Controller;
use App\Mail\LeaveApprovedMail;
use App\Models\Employee;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ASDSLeaveController extends Controller
{
    public function index()
    {
        $leaves = Leave::with('employee')
            ->where('leave_status', 'Pending ASDS')
            ->orderBy('date_applied', 'asc')
            ->get();

        $processed = Leave::with('employee')
            ->whereIn('leave_status', ['Approved', 'Declined'])
            ->orderBy('updated_at', 'desc')
            ->take(20)
            ->get();

        return view('asds.leave.index', compact('leaves', 'processed'));
    }

    public function show(int $id)
    {
        $leave = Leave::with(['employee', 'deptHead', 'approvedBy', 'aoApprover', 'asdsApprover'])
            ->findOrFail($id);

        return view('asds.leave.show', compact('leave'));
    }

    public function approve(Request $request, int $id)
    {
        // $request->validate([
        //     'asds_days_with_pay'    => 'nullable|string',
        //     'asds_days_without_pay' => 'nullable|string',
        //     'asds_others'           => 'nullable|string|max:100',
        // ]);

        $leave = Leave::with('employee')->findOrFail($id);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Build full name for e-signature watermark
        $employee  = Employee::where('user_id', $user->id)->first();
        $asdsName  = $employee
            ? strtoupper($employee->full_name)
            : strtoupper($user->username);

        $leave->update([
            'leave_status'          => 'Approved',
            'asds_id'               => $user->id,
            'asds_action'           => 'Approved',
            'asds_at'               => now(),
            'asds_esign_name'       => $asdsName,
            'asds_esign_path'       => $user->e_signature,
            // 'asds_days_with_pay'    => $request->asds_days_with_pay,
            // 'asds_days_without_pay' => $request->asds_days_without_pay,
            // 'asds_others'           => $request->asds_others,
        ]);

        // ── Send email to employee ─────────────────────────────────────────
        $this->sendApprovalEmail($leave);

        return redirect()->route('asds.leave.index')
            ->with('success', 'Leave fully approved. Email sent to employee.');
    }

    public function decline(Request $request, int $id)
    {
        $request->validate([
            'asds_disapproval' => 'required|string',
        ]);

        $leave = Leave::with('employee')->findOrFail($id);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $leave->update([
            'leave_status'     => 'Declined',
            'asds_id'          => $user->id,
            'asds_action'      => 'Declined',
            'asds_at'          => now(),
            'asds_disapproval' => $request->asds_disapproval,
        ]);

        // ── Send declined email to employee ───────────────────────────────
        $this->sendApprovalEmail($leave);

        return redirect()->route('asds.leave.index')
            ->with('success', 'Leave declined. Employee has been notified.');
    }

    // ── Private: Send email ────────────────────────────────────────────────
    private function sendApprovalEmail(Leave $leave): void
    {
        try {
            // Get employee's email
            $employeeEmail = $leave->employee?->gov_email;

            if (! $employeeEmail) {
                return;
            }

            Mail::to($employeeEmail)->send(new LeaveApprovedMail($leave));

        } catch (\Exception $e) {
            // Fail silently — don't block on mail error
            Log::error('Leave approval email failed: ' . $e->getMessage());
        }
    }
}
