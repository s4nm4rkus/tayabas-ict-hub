<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Mail\EmployeeCredentialsMail;
use App\Models\EducationalBg;
use App\Models\Eligibility;
use App\Models\Employee;
use App\Models\EmploymentInfo;
use App\Models\Role;
use App\Models\Salary;
use App\Models\ServiceRecord;
use App\Models\SubPosition;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EmployeeImport;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['user', 'employment'])->orderBy('last_name');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('last_name', 'like', '%'.$request->search.'%')
                    ->orWhere('first_name', 'like', '%'.$request->search.'%')
                    ->orWhere('gov_email', 'like', '%'.$request->search.'%');
            });
        }

        $employees = $query->get();

        return view('hr.employees.index', compact('employees'));
    }

    public function show(string $id)
    {
        $employee = Employee::with([
            'user', 'employment', 'education',
            'eligibility', 'serviceRecords', 'leaves',
        ])->where('user_id', $id)->firstOrFail();

        return view('hr.employees.show', compact('employee'));
    }

    public function create()
    {
        $roles = Role::orderBy('role_desc')->get();

        return view('hr.employees.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'last_name' => 'required|string|max:100',
            'first_name' => 'required|string|max:100',
            'gov_email' => 'required|email|unique:users,username',
            'user_pos' => 'required|string',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|string',
        ]);

        // Generate Employee ID
        $year = now()->year;
        $prefix = "ICTHUB-{$year}-";
        $last = User::where('user_id', 'like', "{$prefix}%")
            ->orderBy('user_id', 'desc')->first();
        $next = $last
            ? str_pad((int) substr($last->user_id, -4) + 1, 4, '0', STR_PAD_LEFT)
            : '0001';
        $employeeCode = $prefix.$next;

        // Generate password
        $password = $request->first_name
            .($request->birthdate
                ? Carbon::parse($request->birthdate)->format('mdY')
                : 'ICThub@123');

        // Create user account
        $user = User::create([
            'user_id' => $employeeCode,
            'username' => $request->gov_email,
            'password' => Hash::make($password),
            'user_pos' => $request->user_pos,
            'user_stat' => 'Enabled',
            'pass_change' => false,
        ]);

        // Handle photo
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
        }

        // Create employee info
        $employee = Employee::create([
            'user_id' => $user->id,
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'ex_name' => $request->ex_name,
            'gender' => $request->gender,
            'birthdate' => $request->birthdate,
            'place_of_birth' => $request->place_of_birth,
            'contact_num' => $request->contact_num,
            'bp_no' => $request->bp_no,
            'disability' => $request->disability,
            'gov_email' => $request->gov_email,
            'employee_no' => $request->employee_no,
            'philhealth' => $request->philhealth,
            'pagibig' => $request->pagibig,
            'TIN' => $request->TIN,
            'street' => $request->street,
            'street_brgy' => $request->street_brgy,
            'municipality' => $request->municipality,
            'province' => $request->province,
            'region' => $request->region,
            'photo_path' => $photoPath,
        ]);

        // Send credentials email
        try {
            Mail::to($request->gov_email)->send(
                new EmployeeCredentialsMail($employee, $user, $password)
            );
        } catch (\Exception $e) {
            // fail silently — don't block on mail error
        }

        return redirect()->route('hr.employees.index')
            ->with('success', "Employee {$employee->full_name} added. Login credentials sent to {$request->gov_email}.");
    }

    public function edit(string $id)
    {
        $employee = Employee::with([
            'user', 'employment', 'education', 'eligibility', 'serviceRecords',
        ])->where('user_id', $id)->firstOrFail();

        $roles = Role::orderBy('role_desc')->get();
        $subPositions = SubPosition::orderBy('main_pos')->get();
        $salaryGrades = Salary::orderBy('salary_grade')->get();

        return view('hr.employees.edit', compact(
            'employee',
            'roles',
            'subPositions',
            'salaryGrades'
        ));
    }

    public function update(Request $request, string $id)
    {
        $employee = Employee::where('user_id', $id)->firstOrFail();

        $request->validate([
            'last_name' => 'required|string|max:100',
            'first_name' => 'required|string|max:100',
            'gov_email' => 'required|email',
            'birthdate' => 'nullable|date',
        ]);

        // Handle photo
        if ($request->hasFile('photo')) {
            $employee->photo_path = $request->file('photo')->store('photos', 'public');
        }

        // Update employee info
        $employee->update([
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'ex_name' => $request->ex_name,
            'gender' => $request->gender,
            'birthdate' => $request->birthdate,
            'place_of_birth' => $request->place_of_birth,
            'contact_num' => $request->contact_num,
            'bp_no' => $request->bp_no,
            'disability' => $request->disability,
            'gov_email' => $request->gov_email,
            'employee_no' => $request->employee_no,
            'philhealth' => $request->philhealth,
            'pagibig' => $request->pagibig,
            'TIN' => $request->TIN,
            'street' => $request->street,
            'street_brgy' => $request->street_brgy,
            'municipality' => $request->municipality,
            'province' => $request->province,
            'region' => $request->region,
            'photo_path' => $employee->photo_path,
        ]);

        // Update user email/position (HR cannot change account status)
        $employee->user->update([
            'username' => $request->gov_email,
            'user_pos' => $request->user_pos,
        ]);

        // Employment info
        EmploymentInfo::updateOrCreate(
            ['user_id' => $employee->id],
            [
                'position' => $request->user_pos,
                'sub_position' => $request->sub_position,
                'nature_appoint' => $request->nature_appoint,
                'status_appoint' => $request->status_appoint,
                'date_orig_appoint' => $request->date_orig_appoint,
                'salary_grade' => $request->salary_grade,
                'salary_step' => $request->salary_step,
                'salary_effect_date' => $request->salary_effect_date,
                'plantilla_item_no' => $request->plantilla_item_no,
                'plantilla_inclu' => $request->plantilla_inclu,
                'school_office_assign' => $request->school_office_assign,
                'school_detailed_office_assign' => $request->school_detailed_office_assign,
                'vice' => $request->vice,
                'vice_reason' => $request->vice_reason,
                'designated_from' => $request->designated_from,
                'designated_to' => $request->designated_to,
                'separation' => $request->separation,
                'separation_date' => $request->separation_date,
            ]
        );

        // Education
        EducationalBg::updateOrCreate(
            ['user_id' => $employee->id],
            [
                'elementary' => $request->elementary,
                'elem_duration' => $request->elem_duration,
                'secondary' => $request->secondary,
                'second_duration' => $request->second_duration,
                'college' => $request->college,
                'college_school' => $request->college_school,
                'college_duration' => $request->college_duration,
                'vocational' => $request->vocational,
                'voc_school' => $request->voc_school,
                'voca_duration' => $request->voca_duration,
                'masters_degree' => $request->masters_degree,
                'master_duration' => $request->master_duration,
                'master_units' => $request->master_units,
                'doc_degree' => $request->doc_degree,
                'doc_duration' => $request->doc_duration,
                'doc_units' => $request->doc_units,
            ]
        );

        // Eligibility
        Eligibility::updateOrCreate(
            ['user_id' => $employee->id],
            [
                'type_eligibility' => $request->type_eligibility,
                'date_issue' => $request->date_issue,
                'validity' => $request->validity,
            ]
        );

        return redirect()->route('hr.employees.show', $id)
            ->with('success', 'Employee updated successfully.');
    }

    public function storeServiceRecord(Request $request, string $id)
    {
        $employee = Employee::where('user_id', $id)->firstOrFail();

        ServiceRecord::create([
            'user_id' => $employee->id,
            'position' => $request->position,
            'designation' => $request->designation,
            'station' => $request->station,
            'branch' => $request->branch,
            'salary_grade' => $request->salary_grade,
            'salary_step' => $request->salary_step,
            'service_status' => $request->service_status,
            'inclu_from' => $request->inclu_from,
            'inclu_to' => $request->inclu_to,
            'separation' => $request->separation,
        ]);

        return redirect()->route('hr.employees.edit', $id)
            ->with('success', 'Service record added.');
    }

    public function exportCsv()
    {
        $employees = Employee::with(['user', 'employment'])->orderBy('last_name')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=employees_'.now()->format('Ymd').'.csv',
        ];

        $callback = function () use ($employees) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Employee ID', 'Last Name', 'First Name', 'Middle Name',
                'Gender', 'Birthdate', 'Position', 'Email', 'Status',
                'Salary Grade', 'School/Office', 'Date of Appointment',
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


    public function importForm()
    {
        return view('hr.employees.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        $import = new EmployeeImport();
        Excel::import($import, $request->file('file'));

        $message = "{$import->imported} employee(s) imported successfully.";

        if (! empty($import->errors)) {
            return redirect()->route('hr.employees.index')
                ->with('success', $message)
                ->with('import_errors', $import->errors);

        }

        return redirect()->route('hr.employees.index')
            ->with('success', $message)
            ->with('import_passwords', $import->passwords ?? []);
    }

    public function importTemplate()
    {
        $headers = [
            'last_name', 'first_name', 'middle_name', 'extension',
            'gender', 'birthdate', 'place_of_birth', 'contact_num',
            'gov_email', 'position', 'employee_no',
            'philhealth', 'pagibig', 'tin', 'street', 'barangay',
            'municipality', 'province', 'region',
        ];

        $example = [
            'dela Cruz', 'Juan', 'Santos', 'Jr.',
            'Male', '1990-01-15', 'Tayabas, Quezon', '09171234567',
            'juan@deped.gov.ph', 'Teacher I', '2025-001',
            '123456789', '123456789', '123-456-789', '123 Rizal St.',
            'Poblacion', 'Tayabas', 'Quezon', 'Region IV-A',
        ];

        $callback = function () use ($headers, $example) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            fputcsv($file, $example);
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="employee_import_template.csv"',
        ]);
    }

    public function exportPdf()
    {
        $employees = Employee::with(['user', 'employment'])->orderBy('last_name')->get();
        $pdf = Pdf::loadView('hr.employees.export-pdf', compact('employees'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('employees_'.now()->format('Ymd').'.pdf');
    }
}
