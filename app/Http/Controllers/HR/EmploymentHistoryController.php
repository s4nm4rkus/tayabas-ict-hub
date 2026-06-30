<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmploymentHistory;
use App\Models\EmploymentInfo;
use App\Services\ServiceRecordGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmploymentHistoryController extends Controller
{
    public function __construct(
        private ServiceRecordGenerator $generator
    ) {
    }

    /**
     * Store a new employment change.
     * $userId = users.id (e.g. 390) — used by history and service records.
     * EmploymentInfo uses employee.id (e.g. 379) — resolved separately.
     */
    public function store(Request $request, int $userId)
    {
        $request->validate([
            'change_reason'  => 'required|in:PROMOTION,DEMOTION,TRANSFER,RECLASSIFICATION,REINSTATEMENT',
            'position'       => 'required|string|max:100',
            'sub_position'   => 'nullable|string|max:100',
            'salary_grade'   => 'required|integer|min:1|max:33',
            'salary_step'    => 'required|integer|min:1|max:8',
            'nature_appoint' => 'nullable|string|max:100',
            'status_appoint' => 'nullable|string|max:100',
            'station'        => 'nullable|string|max:150',
            'effective_date' => 'required|date',
        ]);

        // Resolve employee.id for updating tbl_employment_info
        $employee   = Employee::where('user_id', $userId)->firstOrFail();
        $employeeId = $employee->id; // 379

        DB::transaction(function () use ($request, $userId, $employeeId) {

            // 1. Close the current active history row
            $current = EmploymentHistory::where('user_id', $userId)
                ->whereNull('end_date')
                ->latest('effective_date')
                ->first();

            if ($current) {
                $current->update([
                    'end_date' => \Carbon\Carbon::parse($request->effective_date)
                        ->subDay()
                        ->toDateString(),
                ]);
            }

            // 2. Determine step_anchor
            if ($request->change_reason === 'DEMOTION' && $current) {
                $stepAnchor = $current->step_anchor ?? $current->effective_date;
            } else {
                $stepAnchor = $request->effective_date;
            }

            // 3. Create new history row (uses users.id = 390)
            EmploymentHistory::create([
                'user_id'        => $userId,
                'position'       => $request->position,
                'sub_position'   => $request->sub_position,
                'salary_grade'   => $request->salary_grade,
                'salary_step'    => $request->salary_step,
                'nature_appoint' => $request->nature_appoint,
                'status_appoint' => $request->status_appoint,
                'station'        => $request->station ?? $current?->station,
                'effective_date' => $request->effective_date,
                'end_date'       => null,
                'change_reason'  => $request->change_reason,
                'step_anchor'    => $stepAnchor instanceof \Carbon\Carbon
                    ? $stepAnchor->toDateString()
                    : $stepAnchor,
                'created_by'     => Auth::id(),
            ]);

            // 4. Update tbl_employment_info (uses employee.id = 379)
            EmploymentInfo::where('user_id', $employeeId)->update([
                'position'             => $request->position,
                'sub_position'         => $request->sub_position,
                'salary_grade'         => $request->salary_grade,
                'salary_step'          => $request->salary_step,
                'nature_appoint'       => $request->nature_appoint,
                'status_appoint'       => $request->status_appoint,
                'salary_effect_date'   => $request->effective_date,
                'school_office_assign' => $request->station ?? optional($current)->station,
            ]);

            // 5. Regenerate service records (uses users.id = 390)
            $this->generator->generate($employeeId, $userId);
        });

        return back()->with('success', 'Employment change recorded and service records updated.');
    }

    /**
     * Show full history timeline for an employee.
     */
    public function index(int $userId)
    {
        $employee  = Employee::where('user_id', $userId)->firstOrFail();
        $histories = EmploymentHistory::where('user_id', $userId)
            ->orderByDesc('effective_date')
            ->get();

        return view('hr.employees.history', compact('employee', 'histories'));
    }

    /**
     * Delete a history entry and regenerate service records.
     */
    public function destroy(int $userId, int $historyId)
    {
        $employee   = Employee::where('user_id', $userId)->firstOrFail();
        $employeeId = $employee->id; // 379

        $history = EmploymentHistory::where('user_id', $userId)
            ->findOrFail($historyId);

        $count = EmploymentHistory::where('user_id', $userId)->count();

        if ($count <= 1) {
            return back()->with('error', 'Cannot delete the only history entry.');
        }

        DB::transaction(function () use ($history, $userId, $employeeId) {
            if (is_null($history->end_date)) {
                $previous = EmploymentHistory::where('user_id', $userId)
                    ->whereNotNull('end_date')
                    ->latest('effective_date')
                    ->first();

                if ($previous) {
                    $previous->update(['end_date' => null]);
                }
            }

            $history->delete();
            app(ServiceRecordGenerator::class)->generate($employeeId, $userId); // ← fixed
        });

        return back()->with('success', 'History entry removed and service records updated.');
    }
}
