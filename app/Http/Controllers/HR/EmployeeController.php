<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Role;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['user', 'employment'])
                         ->orderBy('last_name');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('last_name',  'like', '%'.$request->search.'%')
                  ->orWhere('first_name','like', '%'.$request->search.'%')
                  ->orWhere('gov_email', 'like', '%'.$request->search.'%');
            });
        }

        $employees = $query->get();
        return view('hr.employees.index', compact('employees'));
    }

    public function show(string $id)
    {
        $employee = Employee::with([
            'user','employment','education',
            'eligibility','serviceRecords','leaves',
        ])->where('user_id', $id)->firstOrFail();

        return view('hr.employees.show', compact('employee'));
    }

    public function exportCsv()
    {
        $employees = Employee::with(['user','employment'])->orderBy('last_name')->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=employees_'.now()->format('Ymd').'.csv',
        ];

        $callback = function() use ($employees) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Employee ID','Last Name','First Name','Middle Name',
                'Gender','Birthdate','Position','Email','Status',
                'Salary Grade','School/Office','Date of Appointment',
            ]);
            foreach ($employees as $emp) {
                fputcsv($file, [
                    $emp->user?->user_id,
                    $emp->last_name,
                    $emp->first_name,
                    $emp->middle_name,
                    $emp->gender,
                    $emp->birthdate?->format('Y-m-d'),
                    $emp->employment?->position ?? $emp->user?->user_pos,
                    $emp->gov_email,
                    $emp->user?->user_stat,
                    $emp->employment?->salary_grade,
                    $emp->employment?->school_office_assign,
                    $emp->employment?->date_orig_appoint,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        $employees = Employee::with(['user','employment'])->orderBy('last_name')->get();
        $pdf = Pdf::loadView('hr.employees.export-pdf', compact('employees'));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download('employees_'.now()->format('Ymd').'.pdf');
    }
}