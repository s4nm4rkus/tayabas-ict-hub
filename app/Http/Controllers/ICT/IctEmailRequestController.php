<?php

namespace App\Http\Controllers\ICT;

use App\Http\Controllers\Controller;
use App\Models\IctEmailRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IctEmailRequestController extends Controller
{
    // ────────────────────────────────────────────────────────────────────────
    //  SUBMIT  POST /ict/email-form
    // ────────────────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date'           => 'required|date',
            'request_type'   => 'required|in:New Email,Reset Email,Activation of Office 365',
            'name'           => 'required|string|max:200',
            'email'          => 'required|email|max:200',
            'phone'          => 'required|digits:11',
            'school'         => 'required|string|max:200',
        ]);

        IctEmailRequest::create([
            'ticket_no'      => IctEmailRequest::generateTicketNo(),
            'user_id'        => Auth::id(),
            'date_reported'  => $validated['date'],
            'request_type'   => $validated['request_type'],
            'full_name'      => $validated['name'],
            'personal_email' => $validated['email'],
            'cellphone'      => $validated['phone'],
            'school_name'    => $validated['school'],
            'status'         => 'Pending',
        ]);

        return redirect()
            ->route('ict.email-form')
            ->with('success', 'Your email request has been submitted. The ICT Unit will respond within 24 hours.');
    }

    // ────────────────────────────────────────────────────────────────────────
    //  ADMIN — list all email requests
    //  GET /admin/ict-email-requests
    // ────────────────────────────────────────────────────────────────────────
    public function adminIndex(Request $request)
    {
        $query = IctEmailRequest::with('user')
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('request_type')) {
            $query->where('request_type', $request->request_type);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('ticket_no', 'like', "%{$s}%")
                  ->orWhere('full_name', 'like', "%{$s}%")
                  ->orWhere('school_name', 'like', "%{$s}%");
            });
        }

        $tickets = $query->paginate(15)->withQueryString();

        $counts = [
            'all'         => IctEmailRequest::count(),
            'pending'     => IctEmailRequest::where('status', 'Pending')->count(),
            'in_progress' => IctEmailRequest::where('status', 'In Progress')->count(),
            'resolved'    => IctEmailRequest::where('status', 'Resolved')->count(),
        ];

        return view('ict.admin.email-requests.index', compact('tickets', 'counts'));
    }

    // ────────────────────────────────────────────────────────────────────────
    //  ADMIN — show single request
    // ────────────────────────────────────────────────────────────────────────
    public function adminShow(IctEmailRequest $ictEmailRequest)
    {
        $ictEmailRequest->load('user.employee');
        return view('ict.admin.email-requests.show', compact('ictEmailRequest'));
    }

    // ────────────────────────────────────────────────────────────────────────
    //  ADMIN — update status + notes
    // ────────────────────────────────────────────────────────────────────────
    public function adminUpdate(Request $request, IctEmailRequest $ictEmailRequest)
    {
        $validated = $request->validate([
            'status'      => 'required|in:Pending,In Progress,Resolved,Closed,Cancelled',
            'admin_notes' => 'nullable|string',
        ]);

        $ictEmailRequest->update([
            'status'      => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? $ictEmailRequest->admin_notes,
            'resolved_at' => in_array($validated['status'], ['Resolved', 'Closed'])
                ? ($ictEmailRequest->resolved_at ?? now())
                : null,
        ]);

        return back()->with('success', "Request {$ictEmailRequest->ticket_no} updated to {$validated['status']}.");
    }

    // ────────────────────────────────────────────────────────────────────────
    //  ADMIN — delete
    // ────────────────────────────────────────────────────────────────────────
    public function adminDestroy(IctEmailRequest $ictEmailRequest)
    {
        $no = $ictEmailRequest->ticket_no;
        $ictEmailRequest->delete();
        return back()->with('success', "Request {$no} has been deleted.");
    }
}