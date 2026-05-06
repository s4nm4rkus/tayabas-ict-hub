<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HeadLeaveController extends Controller
{
    public function index()
    {
        $leaves = Leave::with('employee')
            ->where('leave_status', 'Pending Head')
            ->orderBy('date_applied', 'asc')
            ->get();

        // ── Fixed: include all statuses after Head endorsement ─────────────
        // So Head can track what happened to leaves they already endorsed
        $processed = Leave::with('employee')
            ->whereNotIn('leave_status', ['Pending Head'])
            ->where(function ($q) {
                $q->whereNotNull('dept_head');
            })
            ->orderBy('updated_at', 'desc')
            ->take(20)
            ->get();

        return view('head.leave.index', compact('leaves', 'processed'));
    }

    public function show(int $id)
    {
        $leave = Leave::with([
            'employee',
            'deptHead',
            'approvedBy',
            'aoApprover',
            'asdsApprover',
        ])->findOrFail($id);

        return view('head.leave.show', compact('leave'));
    }

    public function approve(int $id)
    {
        $leave = Leave::findOrFail($id);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Build full name for e-signature watermark
        $employee = Employee::where('user_id', $user->id)->first();
        $headName = $employee
            ? strtoupper($employee->full_name)
            : strtoupper($user->username);

        $leave->update([
            'leave_status'   => 'Pending HR',
            'dept_head'      => $user->id,
            'head_esign_name' => $headName,
            'head_esign_path' => $user->e_signature,
        ]);

        return redirect()->route('head.leave.index')
            ->with('success', 'Leave endorsed to HR.');
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
            'leave_status'    => 'Declined',
            'remarks'         => $request->remarks,
            'dept_head'       => $user->id,
            'head_esign_name' => null,
        ]);

        return redirect()->route('head.leave.index')
            ->with('success', 'Leave declined.');
    }
}
