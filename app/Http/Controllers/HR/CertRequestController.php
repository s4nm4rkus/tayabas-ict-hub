<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\CertRequest;
// use App\Models\Employee;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertRequestController extends Controller
{
    public function index()
    {
        $pending = CertRequest::with('employee')
                              ->where('req_status', 'Pending HR')
                              ->orderBy('date_req', 'asc')
                              ->get();

        $processed = CertRequest::with('employee')
                                ->whereIn('req_status', ['Accepted', 'Declined'])
                                ->orderBy('updated_at', 'desc')
                                ->take(20)
                                ->get();

        return view('hr.certificates.index', compact('pending', 'processed'));
    }

    public function accept(int $id)
    {
        $certRequest = CertRequest::findOrFail($id);
        $certRequest->update([
            'req_status'   => 'Accepted',
            'approve_by'   => Auth::id(),
            'approve_date' => now()->toDateString(),
            'approve_time' => now()->toTimeString(),
        ]);

        return redirect()->route('hr.certificates.index')
            ->with('success', 'Certificate request accepted.');
    }

    public function decline(int $id)
    {
        $certRequest = CertRequest::findOrFail($id);
        $certRequest->update([
            'req_status' => 'Declined',
            'approve_by' => Auth::id(),
        ]);

        return redirect()->route('hr.certificates.index')
            ->with('success', 'Certificate request declined.');
    }

    public function generatePdf(int $id)
    {
        $certRequest = CertRequest::with([
            'employee.employment',
            'employee.serviceRecords',
            'employee.leaves',
            'employee.points',
            'employee.user',
        ])->findOrFail($id);

        $employee = $certRequest->employee;

        $view = match($certRequest->req_type) {
            'CSR'  => 'hr.certificates.pdf.csr',
            'COE'  => 'hr.certificates.pdf.coe',
            'COEC' => 'hr.certificates.pdf.coec',
            'CNA'  => 'hr.certificates.pdf.cna',
            'CLB'  => 'hr.certificates.pdf.clb',
            default => abort(404),
        };

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            $view, compact('employee', 'certRequest')
        );
        $pdf->setPaper('a4', 'portrait');

        $filename = $certRequest->req_type . '_' .
                    str_replace(' ', '_', $employee->full_name) . '_' .
                    now()->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }
}