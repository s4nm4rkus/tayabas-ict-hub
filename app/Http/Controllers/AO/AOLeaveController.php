<?php

namespace App\Http\Controllers\AO;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AOLeaveController extends Controller
{
    public function index()
    {
        $leaves = Leave::with('employee')
            ->where('leave_status', 'Pending AO')
            ->orderBy('date_applied', 'asc')
            ->get();

        $processed = Leave::with('employee')
            ->whereIn('leave_status', ['Pending ASDS', 'Approved', 'Declined'])
            ->orderBy('updated_at', 'desc')
            ->take(20)
            ->get();

        return view('ao.leave.index', compact('leaves', 'processed'));
    }

    public function show(int $id)
    {
        $leave = Leave::with(['employee', 'deptHead', 'approvedBy', 'aoApprover', 'asdsApprover'])
            ->findOrFail($id);

        return view('ao.leave.show', compact('leave'));
    }

    public function approve(int $id)
    {
        $leave = Leave::findOrFail($id);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Build full name for e-signature watermark
        $employee = Employee::where('user_id', $user->id)->first();
        $aoName   = $employee
            ? strtoupper($employee->full_name)
            : strtoupper($user->username);

        $leave->update([
            'leave_status'  => 'Pending ASDS',
            'ao_id'         => $user->id,
            'ao_action'     => 'Approved',
            'ao_at'         => now(),
            'ao_esign_name' => $aoName,
            'ao_esign_path' => $user->e_signature,
        ]);

        return redirect()->route('ao.leave.index')
            ->with('success', 'Leave approved and forwarded to ASDS.');
    }

    public function decline(Request $request, int $id)
    {
        $request->validate([
            'ao_remarks' => 'required|string|max:150',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $leave = Leave::findOrFail($id);
        $leave->update([
            'leave_status' => 'Declined',
            'ao_id'        => $user->id,
            'ao_action'    => 'Declined',
            'ao_at'        => now(),
            'ao_remarks'   => $request->ao_remarks,
        ]);

        return redirect()->route('ao.leave.index')
            ->with('success', 'Leave declined.');
    }
}
