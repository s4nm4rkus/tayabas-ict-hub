<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\LeaveBalance;
use App\Models\LeaveCreditTransaction;
use App\Services\LeaveCreditService;
use App\Models\Employee;
use App\Services\MonthlyLeaveComputationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RuntimeException;
use App\Imports\OpeningBalanceImport;
use App\Services\OpeningBalanceImportService;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class LeaveBalanceController extends Controller
{
    public function __construct(
        protected LeaveCreditService $leaveCreditService,
        protected MonthlyLeaveComputationService $monthlyComputationService
    ) {
    }

    /**
     * List current balances for all employees who have one.
     * (Employees with no balance yet simply won't appear here — that's
     * expected for anyone who hasn't had a starting balance entered.)
     */
    public function index()
    {
        $balances = LeaveBalance::with('employee')
            ->orderBy('employee_id')
            ->get();

        return view('hr.leave-balances.index', compact('balances'));
    }

    /**
     * Show a single employee's current balance, or indicate none exists
     * yet (so the view can offer the "enter starting balance" form).
     */
    public function show(int $employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        $balance = $this->leaveCreditService->getBalance($employeeId);

        return view('hr.leave-balances.show', compact('balance', 'employeeId', 'employee'));
    }

    /**
     * One-time starting balance entry for an employee with no balance yet.
     */
    public function storeOpeningBalance(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|integer|exists:tbl_employee_info,id',
            'vl_balance'  => 'required|numeric|min:0',
            'sl_balance'  => 'required|numeric|min:0',
            'as_of_date'  => 'required|date',
            'notes'       => 'nullable|string|max:255',
        ]);

        try {
            $this->leaveCreditService->recordOpeningBalance(
                employeeId: (int) $request->employee_id,
                vlBalance: (float) $request->vl_balance,
                slBalance: (float) $request->sl_balance,
                asOfDate: $request->as_of_date,
                createdBy: Auth::id(),
                description: $request->notes,
            );
        } catch (RuntimeException $e) {
            return back()->withErrors(['balance' => $e->getMessage()])->withInput();
        }

        return redirect()
            ->route('hr.leave-balances.show', $request->employee_id)
            ->with('success', 'Opening balance recorded.');
    }

    /**
     * HR correction to an existing balance — every call requires a reason,
     * enforced both here and inside the service itself.
     */
    public function adjust(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|integer|exists:tbl_employee_info,id',
            'vl_delta'    => 'required|numeric',
            'sl_delta'    => 'required|numeric',
            'effective_date' => 'required|date',
            'reason'      => 'required|string|max:255',
        ]);

        try {
            $this->leaveCreditService->manualAdjustment(
                employeeId: (int) $request->employee_id,
                vlDelta: (float) $request->vl_delta,
                slDelta: (float) $request->sl_delta,
                effectiveDate: $request->effective_date,
                createdBy: Auth::id(),
                reason: $request->reason,
            );
        } catch (RuntimeException $e) {
            return back()->withErrors(['balance' => $e->getMessage()])->withInput();
        }

        return redirect()
            ->route('hr.leave-balances.show', $request->employee_id)
            ->with('success', 'Adjustment recorded.');
    }

    /**
     * Show the full leave-credit transaction history for one employee —
     * every opening balance, deduction, adjustment, and monthly earning,
     * in order. Read-only.
     */
    public function history(int $employeeId)
    {
        $employee = Employee::findOrFail($employeeId);

        $transactions = LeaveCreditTransaction::with(['createdBy', 'approvedBy'])
            ->where('employee_id', $employeeId)
            ->orderBy('effective_date')
            ->orderBy('id')
            ->get();

        return view('hr.leave-balances.history', compact('employee', 'employeeId', 'transactions'));
    }

    /**
     * Show the "Compute Monthly" form — HR picks a year/month to run.
     */
    public function monthlyComputationForm()
    {
        return view('hr.leave-balances.monthly-computation');
    }

    /**
     * Run the monthly computation engine for every eligible employee for
     * the selected year/month. Shows a summary: how many were credited,
     * and a list of who was skipped with the specific reason for each —
     * never a silent partial run.
     */
    public function runMonthlyComputation(Request $request)
    {
        $request->validate([
            'year'  => 'required|integer|min:2020|max:2100',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $result = $this->monthlyComputationService->computeForMonth(
            year: (int) $request->year,
            month: (int) $request->month,
            triggeredBy: Auth::id(),
        );

        return view('hr.leave-balances.monthly-computation', [
            'result' => $result,
            'year'   => (int) $request->year,
            'month'  => (int) $request->month,
        ]);
    }

    /**
     * Show the bulk opening-balance upload page.
     */
    public function importForm()
    {
        return view('hr.leave-balances.import');
    }

    /**
     * Download a CSV template with the exact column headings.
     */
    public function importTemplate()
    {
        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['gov_email', 'vl_balance', 'sl_balance', 'as_of_date', 'notes']);
            fputcsv($file, ['juan@deped.gov.ph', '15.250', '8.000', '2026-08-31', 'Migrated from Leave Card']);
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="opening_balance_import_template.csv"',
        ]);
    }

    /**
     * Validate an uploaded file. mode=check  -> report only, saves nothing.
     *                            mode=import -> saves ONLY if every row is clean.
     */
    public function runImport(Request $request, OpeningBalanceImportService $importService)
    {
        $request->validate([
            'file' => 'required|file|max:5120',
            'mode' => 'required|in:check,import',
        ]);

        try {
            $import = new OpeningBalanceImport();
            Excel::import($import, $request->file('file'));
        } catch (Throwable $e) {
            return back()->withErrors(['file' => 'Could not read that file. Please upload the CSV/Excel template.']);
        }

        $rows = $import->rows;

        if ($rows->isEmpty()) {
            return back()->withErrors(['file' => 'The file has no data rows.']);
        }

        $missing = $importService->missingHeaders($rows);
        if (! empty($missing)) {
            return back()->withErrors([
                'file' => 'Missing column(s): '.implode(', ', $missing).'. Please use the template.',
            ]);
        }

        $check    = $importService->validate($rows);
        $imported = false;

        if ($request->mode === 'import' && empty($check['errors']) && ! empty($check['valid'])) {
            try {
                $importService->import($check['valid'], Auth::id());
                $imported = true;
            } catch (RuntimeException $e) {
                return back()->withErrors([
                    'file' => 'Import stopped and NOTHING was saved: '.$e->getMessage(),
                ]);
            }
        }

        return view('hr.leave-balances.import', [
            'result' => [
                'mode'     => $request->mode,
                'total'    => $check['total'],
                'valid'    => $check['valid'],
                'errors'   => $check['errors'],
                'imported' => $imported,
            ],
        ]);
    }

}
