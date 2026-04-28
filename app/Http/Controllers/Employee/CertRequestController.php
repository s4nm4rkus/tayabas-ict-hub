<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\CertRequest;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertRequestController extends Controller
{
    public function index()
    {
        $employee = Employee::where('user_id', Auth::id())->firstOrFail();
        $requests = CertRequest::where('user_id', $employee->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('employee.certificates.index', compact('requests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'req_type' => 'required|in:CSR,COE,COEC,CNA,CLB',
        ]);

        $employee = Employee::where('user_id', Auth::id())->firstOrFail();

        CertRequest::create([
            'user_id' => $employee->id,
            'req_type' => $request->req_type,
            'date_req' => now()->toDateString(),
            'time_req' => now()->toTimeString(),
            'req_status' => 'Pending HR',
        ]);

        return redirect()->route('employee.certificates.index')
            ->with('success', 'Certificate request submitted successfully.');
    }
}
