<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Role;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function index()
    {
        $employee = Employee::where('user_id', Auth::id())->firstOrFail();
        $leaves   = Leave::where('user_id', $employee->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('employee.leave.index', compact('leaves'));
    }

    public function create()
    {
        $employee   = Employee::with('employment')->where('user_id', Auth::id())->first();
        $leaveTypes = LeaveType::orderBy('leavetype')->get();

        return view('employee.leave.create', compact('leaveTypes', 'employee'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'department'      => 'required|string',
            'salary'          => 'required|string',
            'leave_types'     => 'required|array|min:1',
            'start_date'      => 'required|date',
            'end_date'        => 'required|date|after_or_equal:start_date',
            'number_of_days'  => 'required|string',
            'inclusive_dates' => 'required|string',
            'commutation'     => 'required|string',
            'remarks'         => 'nullable|string|max:100',
            'leavefile'       => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        $employee = Employee::where('user_id', Auth::id())->firstOrFail();
        $user     = Auth::user();

        $start     = Carbon::parse($request->start_date);
        $end       = Carbon::parse($request->end_date);
        $totalDays = $start->diffInWeekdays($end) + 1;

        $role    = Role::where('role_desc', $user->user_pos)->first();
        $roleCat = $role?->role_cat ?? 'Non-Teaching';
        $status  = $roleCat === 'Teaching' ? 'Pending HR' : 'Pending Head';

        $leaveTypesStr = implode(',', $request->leave_types);

        $details = [];
        if ($request->filled('within_ph_text')) {
            $details[] = 'Within Philippines:' . $request->within_ph_text;
        }
        if ($request->filled('abroad_text')) {
            $details[] = 'Abroad:' . $request->abroad_text;
        }
        if ($request->filled('in_hospital_text')) {
            $details[] = 'In Hospital:' . $request->in_hospital_text;
        }
        if ($request->filled('out_patient_text')) {
            $details[] = 'Out Patient:' . $request->out_patient_text;
        }
        if ($request->filled('special_leave_bw')) {
            $details[] = 'Special Leave (Women):' . $request->special_leave_bw;
        }
        if ($request->has('completion_masters')) {
            $details[] = "Completion of Master's Degree";
        }
        if ($request->has('bar_board_exam')) {
            $details[] = 'BAR/Board Examination Review';
        }
        if ($request->has('monetization')) {
            $details[] = 'Monetization of Leave Credits';
        }
        if ($request->has('terminal_leave')) {
            $details[] = 'Terminal Leave';
        }
        if ($request->filled('others_text')) {
            $details[] = 'Others:' . $request->others_text;
        }

        $attachmentPath = null;
        if ($request->hasFile('leavefile')) {
            $attachmentPath = $request->file('leavefile')->store('leave_attachments', 'public');
        }

        Leave::create([
            'user_id'         => $employee->id,
            'fullname'        => $employee->full_name,
            'position'        => $user->user_pos,
            'leavetype'       => $leaveTypesStr,
            'date_applied'    => now(),
            'start_date'      => $request->start_date,
            'end_date'        => $request->end_date,
            'total_days'      => $totalDays,
            'leave_status'    => $status,
            'remarks'         => $request->remarks,
            'leavefile'       => $attachmentPath,
            'department'      => $request->department,
            'salary'          => $request->salary,
            'leave_types'     => $leaveTypesStr,
            'leave_details'   => implode(';', $details),
            'commutation'     => $request->commutation,
            'number_of_days'  => $request->number_of_days,
            'inclusive_dates' => $request->inclusive_dates,
            'employee_esign_path'  => $user->e_signature,
        ]);

        return redirect()->route('employee.leave.index')
            ->with('success', 'Leave application (Form 6) submitted successfully.');
    }

    public function show(int $id)
    {
        $employee = Employee::where('user_id', Auth::id())->firstOrFail();
        $leave    = Leave::with([
            'employee', 'employee.user',
            'deptHead', 'approvedBy',
            'aoApprover', 'asdsApprover',
        ])->where('id', $id)
          ->where('user_id', $employee->id)
          ->firstOrFail();

        return view('employee.leave.show', compact('leave'));
    }

    public function cancel(int $id)
    {
        $employee = Employee::where('user_id', Auth::id())->firstOrFail();
        $leave    = Leave::where('id', $id)
            ->where('user_id', $employee->id)
            ->firstOrFail();

        if (! $leave->isCancellable()) {
            return redirect()->route('employee.leave.index')
                ->with('error', 'Cannot cancel a leave that is already being processed.');
        }

        $leave->update(['leave_status' => 'Cancelled']);

        return redirect()->route('employee.leave.index')
            ->with('success', 'Leave application cancelled.');
    }

    public function print(int $id)
    {
        $employee = Employee::where('user_id', Auth::id())->firstOrFail();
        $leave    = Leave::with([
            'employee', 'employee.user',
            'deptHead', 'approvedBy',
            'aoApprover', 'asdsApprover',
        ])->where('id', $id)
          ->where('user_id', $employee->id)
          ->firstOrFail();

        return view('employee.leave.form6_print', compact('leave'));
    }

    public function downloadPdf(int $id)
    {
        $employee = Employee::where('user_id', Auth::id())->firstOrFail();
        $leave    = Leave::with([
            'employee', 'employee.user',
            'deptHead', 'approvedBy',
            'aoApprover', 'asdsApprover',
        ])->where('id', $id)
          ->where('user_id', $employee->id)
          ->where('leave_status', 'Approved')
          ->firstOrFail();

        $pdf = Pdf::loadView('pdf.form6_pdf', compact('leave'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => true,
                'defaultFont'          => 'Arial',
            ]);

        return $pdf->download('Form6_' . str_replace(' ', '_', $leave->fullname) . '_' . now()->format('Ymd') . '.pdf');
    }
}
