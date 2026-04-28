<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function index()
    {
        $employee = Employee::where('user_id', Auth::id())->firstOrFail();
        $leaves = Leave::where('user_id', $employee->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('employee.leave.index', compact('leaves'));
    }

    public function create()
    {
        $leaveTypes = LeaveType::orderBy('leavetype')->get();

        return view('employee.leave.create', compact('leaveTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'leavetype' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'remarks' => 'nullable|string|max:50',
        ]);

        $employee = Employee::where('user_id', Auth::id())->firstOrFail();
        $user = Auth::user();

        // Calculate total days
        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $totalDays = $start->diffInWeekdays($end) + 1;

        // Determine initial status based on role_cat
        $role = Role::where('role_desc', $user->user_pos)->first();
        $roleCat = $role?->role_cat ?? 'Teaching';

        $status = $roleCat === 'Teaching' ? 'Pending HR' : 'Pending Head';

        // Handle attachment
        $attachmentPath = null;
        if ($request->hasFile('leavefile')) {
            $attachmentPath = $request->file('leavefile')
                ->store('leave_attachments', 'public');
        }

        Leave::create([
            'user_id' => $employee->id,
            'fullname' => $employee->full_name,
            'position' => $user->user_pos,
            'leavetype' => $request->leavetype,
            'date_applied' => now(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_days' => $totalDays,
            'leave_status' => $status,
            'remarks' => $request->remarks,
            'leavefile' => $attachmentPath,
        ]);

        return redirect()->route('employee.leave.index')
            ->with('success', 'Leave application submitted successfully.');
    }

    public function cancel(int $id)
    {
        $employee = Employee::where('user_id', Auth::id())->firstOrFail();
        $leave = Leave::where('id', $id)
            ->where('user_id', $employee->id)
            ->firstOrFail();

        if (! in_array($leave->leave_status, ['Pending HR', 'Pending Head'])) {
            return redirect()->route('employee.leave.index')
                ->with('error', 'Cannot cancel a leave that is already processed.');
        }

        $leave->update(['leave_status' => 'Cancelled']);

        return redirect()->route('employee.leave.index')
            ->with('success', 'Leave application cancelled.');
    }
}
