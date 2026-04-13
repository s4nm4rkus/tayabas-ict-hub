<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use App\Models\Leave;
// use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HeadLeaveController extends Controller
{
    public function index()
    {
        // Head only sees Non-Teaching staff leaves
        $leaves = Leave::with('employee')
                       ->where('leave_status', 'Pending Head')
                       ->orderBy('date_applied', 'asc')
                       ->get();

        $processed = Leave::with('employee')
                          ->whereIn('leave_status', ['Approved', 'Declined'])
                          ->orderBy('updated_at', 'desc')
                          ->take(20)
                          ->get();

        return view('head.leave.index', compact('leaves', 'processed'));
    }

    public function approve(int $id)
    {
        $leave = Leave::findOrFail($id);
        $leave->update([
            'leave_status' => 'Pending HR',
            'dept_head'    => Auth::id(),
        ]);

        return redirect()->route('head.leave.index')
            ->with('success', 'Leave endorsed to HR.');
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
            'dept_head'    => Auth::id(),
        ]);

        return redirect()->route('head.leave.index')
            ->with('success', 'Leave declined.');
    }
}