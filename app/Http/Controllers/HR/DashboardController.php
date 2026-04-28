<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Board;
use App\Models\CertRequest;
use App\Models\Employee;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Existing stats ────────────────────────────────────────────────────
        $pendingLeaves = Leave::where('leave_status', 'Pending HR')->count();
        $pendingCerts = CertRequest::where('req_status', 'Pending HR')->count();
        $totalEmployees = Employee::count();
        $approvedToday = Leave::where('leave_status', 'Approved')
            ->whereDate('updated_at', today())->count();
        $presentToday = Attendance::whereDate('t_date', today())->count();

        $recentLeaves = Leave::with('employee')
            ->where('leave_status', 'Pending HR')
            ->orderBy('created_at', 'desc')
            ->take(5)->get();

        $announcements = Board::with('user')
            ->orderBy('date_time', 'desc')
            ->take(3)->get();

        // ── ANALYTICS 1: Attendance breakdown this month ──────────────────────
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();
        $monthAttendance = Attendance::whereBetween('t_date', [$monthStart, $monthEnd])->get();

        $onTime = $monthAttendance->filter(fn ($a) => $a->am_time_in && Carbon::parse($a->am_time_in)->format('H:i') <= '08:05'
        )->count();

        $late = $monthAttendance->filter(fn ($a) => $a->am_time_in && Carbon::parse($a->am_time_in)->format('H:i') > '08:05'
        )->count();

        $workingDaysSoFar = 0;
        $cursor = $monthStart->copy();
        while ($cursor->lte(now())) {
            if ($cursor->isWeekday()) {
                $workingDaysSoFar++;
            }
            $cursor->addDay();
        }

        $absent = max(0, ($totalEmployees * $workingDaysSoFar) - $monthAttendance->count());
        $avgHours = round($monthAttendance->avg('total_hours') ?? 0, 2);

        $attendanceStats = [
            'total_records' => $monthAttendance->count(),
            'on_time' => $onTime,
            'late' => $late,
            'absent' => $absent,
            'avg_hours' => $avgHours,
        ];

        // ── ANALYTICS 2: Leave type breakdown this year ───────────────────────
        $leaveTypeStats = Leave::whereYear('created_at', now()->year)
            ->select('leavetype', DB::raw('count(*) as total'))
            ->groupBy('leavetype')
            ->orderByDesc('total')
            ->get();

        $topLeaveRequester = Leave::whereYear('created_at', now()->year)
            ->select('fullname', DB::raw('count(*) as total'))
            ->groupBy('fullname')
            ->orderByDesc('total')
            ->first();

        // ── ANALYTICS 3: Certificate volume ──────────────────────────────────
        $certStats = [
            'pending' => CertRequest::where('req_status', 'Pending HR')->count(),
            'released' => CertRequest::where('req_status', 'Accepted')->count(),
            'rejected' => CertRequest::where('req_status', 'Declined')->count(),
            'this_month' => CertRequest::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        // ── ANALYTICS 4: Headcount trend — last 12 months ────────────────────
        $headcountTrend = collect();
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i)->format('Y-m');
            $count = Employee::where(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), '<=', $month
            )->count();
            $headcountTrend->push(['month' => $month, 'count' => $count]);
        }

        $headcountYTD = Employee::whereYear('created_at', now()->year)->count();
        $retiredYTD = 0;

        // ── ANALYTICS 5: Retirement Watch ────────────────────────────────────
        $retirementAge = 60;
        $retirementWatch = Employee::select([
            'tbl_employee_info.*',
            DB::raw('TIMESTAMPDIFF(YEAR, tbl_employee_info.birthdate, CURDATE()) AS current_age'),
            DB::raw('TIMESTAMPDIFF(YEAR, tbl_employment_info.date_orig_appoint, CURDATE()) AS years_of_service'),
            DB::raw("{$retirementAge} - TIMESTAMPDIFF(YEAR, tbl_employee_info.birthdate, CURDATE()) AS years_to_retire"),
        ])
            ->leftJoin('tbl_employment_info', 'tbl_employee_info.id', '=', 'tbl_employment_info.user_id')
            ->whereNotNull('tbl_employee_info.birthdate')
            ->havingRaw('current_age BETWEEN ? AND ?', [$retirementAge - 5, $retirementAge + 1])
            ->orderByRaw('years_to_retire ASC')
            ->get();

        return view('hr.dashboard', compact(
            'pendingLeaves', 'pendingCerts', 'totalEmployees',
            'approvedToday', 'presentToday',
            'recentLeaves', 'announcements',
            'attendanceStats', 'leaveTypeStats', 'topLeaveRequester',
            'certStats', 'headcountTrend', 'headcountYTD', 'retiredYTD',
            'retirementWatch',
        ));
    }
}
