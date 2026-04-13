<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use App\Models\Role;
use App\Models\SubPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Mail\EmployeeCredentialsMail;
use Illuminate\Support\Facades\Mail;
use App\Models\EmploymentInfo;

class EmployeeController extends Controller
{
  
    public function index()
    {
        $employees = Employee::with(['user', 'employment'])->orderBy('last_name')->get();
        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        $roles = Role::all();
        $subPositions = SubPosition::all();
        return view('admin.employees.create', compact('roles', 'subPositions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'gov_email'  => 'required|email|unique:users,username',
            'user_pos'   => 'required|string',
            'gender'     => 'required|string',
            'birthdate'  => 'required|date',
        ]);

        $initialPassword = Str::random(10);
        $employeeCode    = $this->generateEmployeeCode();

        $user = DB::transaction(function () use ($request, $initialPassword, $employeeCode) {

            // Create user — standard integer id, custom code in user_id column
            $user = User::create([
                'user_id'     => $employeeCode,
                'username'    => $request->gov_email,
                'password'    => Hash::make($initialPassword),
                'user_pos'    => $request->user_pos,
                'user_stat'   => 'Enabled',
                'pass_change' => false,
            ]);

            // Handle photo upload
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('photos', 'public');
            }

            // Create employee — user_id is now the integer users.id
        $employee = Employee::create([
                    'user_id'        => $user->id,
                    'first_name'     => $request->first_name,
                    'last_name'      => $request->last_name,
                    'middle_name'    => $request->middle_name,
                    'ex_name'        => $request->ex_name,
                    'gender'         => $request->gender,
                    'birthdate'      => $request->birthdate,
                    'place_of_birth' => $request->place_of_birth,
                    'contact_num'    => $request->contact_num,
                    'gov_email'      => $request->gov_email,
                    'employee_no'    => $request->employee_no,
                    'philhealth'     => $request->philhealth,
                    'pagibig'        => $request->pagibig,
                    'TIN'            => $request->TIN,
                    'street'         => $request->street,
                    'street_brgy'    => $request->street_brgy,
                    'municipality'   => $request->municipality,
                    'province'       => $request->province,
                    'region'         => $request->region,
                    'disability'     => $request->disability,
                    'photo_path'     => $photoPath,
                    'bp_no'          => $request->bp_no,
                    'date_encoded'   => now(),
                ]);

                
                EmploymentInfo::create([
                'user_id'  => $employee->id,
                'position' => $request->user_pos,
            ]);
            return $user;
        });

        Mail::to($request->gov_email)->send(
            new EmployeeCredentialsMail($user, $initialPassword)
        );

        return redirect()->route('admin.employees.index')
            ->with('success', "Employee {$employeeCode} added. Credentials sent to {$request->gov_email}.");
    }

    // public function show(string $id)
    // {
    //     $employee = Employee::with([
    //         'employment',
    //         'education',
    //         'eligibility',
    //         'serviceRecords',
    //         'attachments',
    //         'leaves',
    //     ])->findOrFail($id);

    //     return view('admin.employees.show', compact('employee'));
    // }

    // public function edit(string $id)
    // {
    //     $employee = Employee::with('employment')->findOrFail($id);
    //     $roles = Role::all();
    //     $subPositions = SubPosition::all();
    //     return view('admin.employees.edit', compact('employee', 'roles', 'subPositions'));
    // }

    public function show(string $id)
{
    $employee = Employee::with([
        'employment',
        'education',
        'eligibility',
        'serviceRecords',
        'attachments',
        'leaves',
    ])->where('user_id', $id)->firstOrFail();

    return view('admin.employees.show', compact('employee'));
}

public function edit(string $id)
{
    $employee = Employee::with('employment')
        ->where('user_id', $id)
        ->firstOrFail();
    $roles        = Role::orderBy('role_desc')->get();
    $subPositions = SubPosition::orderBy('main_pos')->get();
    $salaryGrades = \App\Models\Salary::orderBy('salary_grade')->get();

    return view('admin.employees.edit', compact(
        'employee', 'roles', 'subPositions', 'salaryGrades'
    ));
}
public function update(Request $request, string $id)
{
    $employee = Employee::with(['employment', 'education', 'eligibility'])
        ->where('user_id', $id)
        ->firstOrFail();

    // Photo
    if ($request->hasFile('photo')) {
        $employee->photo_path = $request->file('photo')->store('photos', 'public');
    }

    // Personal info
    $employee->update($request->only([
        'last_name', 'first_name', 'middle_name', 'ex_name', 'gender',
        'birthdate', 'place_of_birth', 'contact_num', 'gov_email',
        'employee_no', 'philhealth', 'pagibig', 'TIN', 'street',
        'street_brgy', 'municipality', 'province', 'region', 'disability', 'bp_no',
    ]));

    // Account
    $employee->user?->update([
        'username'  => $request->gov_email,
        'user_pos'  => $request->user_pos,
        'user_stat' => $request->user_stat,
    ]);

    // Employment — update or create
    if ($employee->employment) {
        $employee->employment->update(array_merge(
            ['position' => $request->user_pos],
            $request->only([
                'sub_position', 'date_orig_appoint', 'salary_grade',
                'salary_step', 'salary_effect_date', 'vice', 'vice_reason',
                'nature_appoint', 'status_appoint', 'plantilla_item_no',
                'plantilla_inclu', 'school_office_assign',
                'school_detailed_office_assign', 'designated_from',
                'designated_to', 'separation', 'separation_date',
            ])
        ));
    } else {
        EmploymentInfo::create(array_merge(
            ['user_id' => $employee->id, 'position' => $request->user_pos],
            $request->only([
                'sub_position', 'date_orig_appoint', 'salary_grade',
                'salary_step', 'salary_effect_date',
            ])
        ));
    }

    // Education — update or create
    if ($employee->education) {
        $employee->education->update($request->only([
            'elementary', 'elem_duration', 'secondary', 'second_duration',
            'vocational', 'voca_duration', 'college', 'college_school',
            'college_duration', 'masters_degree', 'master_duration',
            'master_units', 'doc_degree', 'doc_duration', 'doc_units',
        ]));
    } else {
        \App\Models\EducationalBg::create(array_merge(
            ['user_id' => $employee->id],
            $request->only([
                'elementary', 'elem_duration', 'secondary', 'second_duration',
                'vocational', 'voca_duration', 'college', 'college_school',
                'college_duration', 'masters_degree', 'master_duration',
                'master_units', 'doc_degree', 'doc_duration', 'doc_units',
            ])
        ));
    }

    // Eligibility — update or create
    if ($employee->eligibility) {
        $employee->eligibility->update($request->only([
            'type_eligibility', 'date_issue', 'validity',
        ]));
    } else {
        \App\Models\Eligibility::create(array_merge(
            ['user_id' => $employee->id],
            $request->only(['type_eligibility', 'date_issue', 'validity'])
        ));
    }

    return redirect()->route('admin.employees.show', $id)
        ->with('success', 'Employee updated successfully.');
}

    public function importForm()
    {
        return view('admin.employees.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        $import = new \App\Imports\EmployeeImport();
        \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('file'));

        //  dd([
        //     'imported' => $import->imported,
        //     'errors'   => $import->errors,
        // ]);

        $message = "{$import->imported} employee(s) imported successfully.";

        if (!empty($import->errors)) {
            return redirect()->route('admin.employees.index')
                ->with('success', $message)
                ->with('import_errors', $import->errors);
        }

        return redirect()->route('admin.employees.index')
            ->with('success', $message);
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

    // Generate display code ICTHUB-YYYY-0001
    private function generateEmployeeCode(): string
    {
        $year   = date('Y');
        $prefix = "ICTHUB-{$year}-";

        $last = User::where('user_id', 'like', "{$prefix}%")
            ->orderBy('user_id', 'desc')
            ->first();

        $next = $last
            ? str_pad((int) substr($last->user_id, -4) + 1, 4, '0', STR_PAD_LEFT)
            : '0001';

        return "{$prefix}{$next}";
    }

  public function exportCsv()
{
    $employees = Employee::with('user')->orderBy('last_name')->get();

    $headers = [
        'Content-Type'        => 'text/csv',
        'Content-Disposition' => 'attachment; filename="employees_' . date('Y-m-d') . '.csv"',
    ];

    $callback = function () use ($employees) {
        $file = fopen('php://output', 'w');

        fputcsv($file, [
            'Employee ID', 'Last Name', 'First Name', 'Middle Name',
            'Extension', 'Gender', 'Birthdate', 'Place of Birth',
            'Contact No.', 'Gov Email', 'Employee No.', 'PhilHealth',
            'Pag-IBIG', 'TIN', 'Street', 'Barangay', 'Municipality',
            'Province', 'Region', 'BP No.', 'Disability',
            'Position', 'Status', 'Date Encoded',
        ]);

        foreach ($employees as $emp) {
            fputcsv($file, [
                $emp->user?->user_id             ?? '—',
                $emp->last_name                  ?? '—',
                $emp->first_name                 ?? '—',
                $emp->middle_name                ?? '—',
                $emp->ex_name                    ?? '—',
                $emp->gender                     ?? '—',
                $emp->birthdate?->format('Y-m-d') ?? '—',
                $emp->place_of_birth             ?? '—',
                $emp->contact_num                ?? '—',
                $emp->gov_email                  ?? '—',
                $emp->employee_no                ?? '—',
                $emp->philhealth                 ?? '—',
                $emp->pagibig                    ?? '—',
                $emp->TIN                        ?? '—',
                $emp->street                     ?? '—',
                $emp->street_brgy                ?? '—',
                $emp->municipality               ?? '—',
                $emp->province                   ?? '—',
                $emp->region                     ?? '—',
                $emp->bp_no                      ?? '—',
                $emp->disability                 ?? '—',
                $emp->user?->user_pos            ?? '—',
                $emp->user?->user_stat           ?? '—',
                $emp->date_encoded?->format('Y-m-d') ?? '—',
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

public function exportPdf()
{
    $employees = Employee::with('user')
        ->orderBy('last_name')
        ->get();

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
        'admin.employees.export-pdf',
        compact('employees')
    );
    $pdf->setPaper('a4', 'landscape');
    return $pdf->download('employees_' . date('Y-m-d') . '.pdf');
}

public function storeServiceRecord(Request $request, string $id)
{
    $employee = Employee::where('user_id', $id)->firstOrFail();

    \App\Models\ServiceRecord::create([
        'user_id'        => $employee->id,
        'inclu_from'     => $request->inclu_from,
        'inclu_to'       => $request->inclu_to,
        'position'       => $request->position,
        'designation'    => $request->designation,
        'station'        => $request->station,
        'branch'         => $request->branch,
        'salary_grade'   => $request->salary_grade,
        'salary_step'    => $request->salary_step,
        'service_status' => $request->service_status,
        'separation'     => $request->separation,
    ]);

    return redirect()->route('admin.employees.edit', $id)
        ->with('success', 'Service record added.')
        ->withFragment('tab-service');
}
}