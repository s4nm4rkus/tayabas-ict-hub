<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Point;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with('employee')->orderBy('t_date', 'desc');

        if ($request->filled('date')) {
            $query->whereDate('t_date', $request->date);
        }
        if ($request->filled('employee_id')) {
            $query->where('user_id', $request->employee_id);
        }

        $attendance = $query->paginate(30);
        $employees  = Employee::orderBy('last_name')->get();

        return view('hr.attendance.index', compact('attendance', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'     => 'required|integer',
            't_date'      => 'required|date',
            'am_time_in'  => 'nullable',
            'am_time_out' => 'nullable',
            'pm_time_in'  => 'nullable',
            'pm_time_out' => 'nullable',
        ]);

        $employee   = Employee::findOrFail($request->user_id);
        $totalHours = $this->calculateHours(
            $request->am_time_in, $request->am_time_out,
            $request->pm_time_in, $request->pm_time_out
        );

        Attendance::updateOrCreate(
            ['user_id' => $employee->id, 't_date' => $request->t_date],
            [
                'fullname'     => $employee->full_name,
                'position'     => $employee->user?->user_pos,
                'am_time_in'   => $request->am_time_in,
                'am_time_out'  => $request->am_time_out,
                'pm_time_in'   => $request->pm_time_in,
                'pm_time_out'  => $request->pm_time_out,
                'total_hours'  => $totalHours,
            ]
        );

        Point::updateOrCreate(
            ['userid' => $employee->id, 't_date' => $request->t_date],
            ['acc_points' => round((0.42 / 8) * $totalHours, 4)]
        );

        return redirect()->route('hr.attendance.index')
            ->with('success', 'Attendance recorded.');
    }

    public function destroy(int $id)
    {
        Attendance::findOrFail($id)->delete();
        return redirect()->route('hr.attendance.index')
            ->with('success', 'Attendance record deleted.');
    }

    public function exportCsv(Request $request)
    {
        $query = Attendance::with('employee')->orderBy('t_date', 'desc');

        if ($request->filled('date'))        $query->whereDate('t_date', $request->date);
        if ($request->filled('employee_id')) $query->where('user_id', $request->employee_id);

        $records = $query->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=attendance_'.now()->format('Ymd').'.csv',
        ];

        $callback = function() use ($records) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Name','Position','Date','AM Time In','AM Time Out',
                'PM Time In','PM Time Out','Total Hours','Points Earned',
            ]);
            foreach ($records as $rec) {
                fputcsv($file, [
                    $rec->fullname,
                    $rec->position,
                    $rec->t_date,
                    $rec->am_time_in  ?? '',
                    $rec->am_time_out ?? '',
                    $rec->pm_time_in  ?? '',
                    $rec->pm_time_out ?? '',
                    $rec->total_hours,
                    round((0.42 / 8) * (float)$rec->total_hours, 4),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importForm()
    {
        return view('hr.attendance.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:10240',
        ]);

        $file      = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        $errors    = [];
        $imported  = 0;

        if ($extension === 'csv') {
            $rows   = array_map('str_getcsv', file($file->getRealPath()));
            $header = array_shift($rows);
            $header = array_map('strtolower', array_map('trim', $header));
        } else {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
            $sheet       = $spreadsheet->getActiveSheet()->toArray();
            $header      = array_map('strtolower', array_map('trim', array_shift($sheet)));
            $rows        = $sheet;
        }

        foreach ($rows as $i => $row) {

            // Skip empty rows
            if (empty(array_filter($row))) continue;

            $data   = array_combine($header, $row);
            $rowNum = $i + 2;

            // Required fields
            if (empty($data['gov_email']) && empty($data['employee_id'])) {
                $errors[] = "Row {$rowNum}: Missing employee identifier (gov_email or employee_id).";
                continue;
            }
            if (empty($data['date'])) {
                $errors[] = "Row {$rowNum}: Missing date.";
                continue;
            }

            // Normalize date → YYYY-MM-DD regardless of input format
            try {
                $parsedDate = \Carbon\Carbon::parse(trim($data['date']))->format('Y-m-d');
            } catch (\Exception $e) {
                $errors[] = "Row {$rowNum}: Invalid date '{$data['date']}'. Use YYYY-MM-DD or MM/DD/YYYY.";
                continue;
            }

            // Find employee
            $employee = null;
            if (!empty($data['gov_email'])) {
                $user = \App\Models\User::where('username', trim($data['gov_email']))->first();
                if ($user) $employee = Employee::where('user_id', $user->id)->first();
            }
            if (!$employee && !empty($data['employee_id'])) {
                $user = \App\Models\User::where('user_id', trim($data['employee_id']))->first();
                if ($user) $employee = Employee::where('user_id', $user->id)->first();
            }

            if (!$employee) {
                $errors[] = "Row {$rowNum}: Employee not found.";
                continue;
            }

            // Normalize time fields — strip any extra spaces
            $amIn  = !empty($data['am_time_in'])  ? trim($data['am_time_in'])  : null;
            $amOut = !empty($data['am_time_out']) ? trim($data['am_time_out']) : null;
            $pmIn  = !empty($data['pm_time_in'])  ? trim($data['pm_time_in'])  : null;
            $pmOut = !empty($data['pm_time_out']) ? trim($data['pm_time_out']) : null;

            $totalHours = $this->calculateHours($amIn, $amOut, $pmIn, $pmOut);

            Attendance::updateOrCreate(
                [
                    'user_id' => $employee->id,
                    't_date'  => $parsedDate,
                ],
                [
                    'fullname'    => $employee->full_name,
                    'position'    => $employee->user?->user_pos,
                    'am_time_in'  => $amIn,
                    'am_time_out' => $amOut,
                    'pm_time_in'  => $pmIn,
                    'pm_time_out' => $pmOut,
                    'total_hours' => $totalHours,
                ]
            );

            Point::updateOrCreate(
                [
                    'userid' => $employee->id,
                    't_date' => $parsedDate,
                ],
                ['acc_points' => round((0.42 / 8) * $totalHours, 4)]
            );

            $imported++;
        }

        return redirect()->route('hr.attendance.index')
            ->with('success', "{$imported} attendance records imported successfully.")
            ->with('import_errors', $errors);
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=attendance_template.csv',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'gov_email','employee_id','date',
                'am_time_in','am_time_out','pm_time_in','pm_time_out',
            ]);
            fputcsv($file, [
                'juan@deped.gov.ph','ICTHUB-2026-0001','2026-04-16',
                '07:30','12:00','13:00','17:00',
            ]);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function calculateHours($amIn, $amOut, $pmIn, $pmOut): float
    {
        $total = 0;
        if ($amIn && $amOut) {
            $total += \Carbon\Carbon::parse($amOut)->diffInMinutes(\Carbon\Carbon::parse($amIn)) / 60;
        }
        if ($pmIn && $pmOut) {
            $total += \Carbon\Carbon::parse($pmOut)->diffInMinutes(\Carbon\Carbon::parse($pmIn)) / 60;
        }
        return round($total, 2);
    }
}