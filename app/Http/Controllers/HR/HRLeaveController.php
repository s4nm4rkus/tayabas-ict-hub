<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Leave;
// use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HRLeaveController extends Controller
{
    public function index()
    {
        $leaves = Leave::with('employee')
                       ->whereIn('leave_status', ['Pending HR'])
                       ->orderBy('date_applied', 'asc')
                       ->get();

        $processed = Leave::with('employee')
                          ->whereNotIn('leave_status', ['Pending HR', 'Pending Head'])
                          ->orderBy('updated_at', 'desc')
                          ->take(20)
                          ->get();

        return view('hr.leave.index', compact('leaves', 'processed'));
    }

    public function approve(int $id)
    {
        $leave = Leave::findOrFail($id);
        $leave->update([
            'leave_status' => 'Approved',
            'approve_by'   => Auth::id(),
            'approve_date' => now()->toDateString(),
            'approve_time' => now()->toTimeString(),
        ]);

        return redirect()->route('hr.leave.index')
            ->with('success', 'Leave approved.');
    }

    public function decline(Request $request, int $id)
    {
        $request->validate([
            'remarks' => 'required|string|max:50',
        ]);

        $leave = Leave::findOrFail($id);
        $leave->update([
            'leave_status' => 'Declined',
            'remarks'      => $request->remarks,
            'approve_by'   => Auth::id(),
        ]);

        return redirect()->route('hr.leave.index')
            ->with('success', 'Leave declined.');
    }
}