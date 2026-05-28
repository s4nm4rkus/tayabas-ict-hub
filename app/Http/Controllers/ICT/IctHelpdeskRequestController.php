<?php

namespace App\Http\Controllers\ICT;

use App\Http\Controllers\Controller;
use App\Models\IctHelpdeskRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IctHelpdeskRequestController extends Controller
{
    // ────────────────────────────────────────────────────────────────────────
    //  SUBMIT  POST /ict/helpdesk-form
    // ────────────────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date_filed'             => 'required|date',
            'requesting_office'      => 'required|string|max:200',
            'requesting_name'        => 'required|string|max:200',
            'details_request'        => 'required|string',
            'date_requested'         => 'required|date',
            'time_requested'         => 'required|string|max:20',
            'specific_instructions'  => 'nullable|string',
        ]);

        IctHelpdeskRequest::create([
            'ticket_no'             => IctHelpdeskRequest::generateTicketNo(),
            'user_id'               => Auth::id(),
            'date_filed'            => $validated['date_filed'],
            'requesting_office'     => $validated['requesting_office'],
            'requesting_name'       => $validated['requesting_name'],
            'details_request'       => $validated['details_request'],
            'date_requested'        => $validated['date_requested'],
            'time_requested'        => $validated['time_requested'],
            'specific_instructions' => $validated['specific_instructions'] ?? null,
            'status'                => 'Pending',
        ]);

        return redirect()
            ->route('ict.helpdesk-form')
            ->with('success', 'Your Help Desk request has been submitted. The ICT Unit will respond within 24 hours.');
    }

    // ────────────────────────────────────────────────────────────────────────
    //  ADMIN — list all help desk requests
    // ────────────────────────────────────────────────────────────────────────
    public function adminIndex(Request $request)
    {
        $query = IctHelpdeskRequest::with('user')
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('ticket_no', 'like', "%{$s}%")
                  ->orWhere('requesting_name', 'like', "%{$s}%")
                  ->orWhere('requesting_office', 'like', "%{$s}%");
            });
        }

        $tickets = $query->paginate(15)->withQueryString();

        $counts = [
            'all'         => IctHelpdeskRequest::count(),
            'pending'     => IctHelpdeskRequest::where('status', 'Pending')->count(),
            'in_progress' => IctHelpdeskRequest::where('status', 'In Progress')->count(),
            'resolved'    => IctHelpdeskRequest::where('status', 'Resolved')->count(),
        ];

        return view('ict.admin.helpdesk-requests.index', compact('tickets', 'counts'));
    }

    // ────────────────────────────────────────────────────────────────────────
    //  ADMIN — show single request
    // ────────────────────────────────────────────────────────────────────────
    public function adminShow(IctHelpdeskRequest $ictHelpdeskRequest)
    {
        $ictHelpdeskRequest->load('user.employee');
        return view('ict.admin.helpdesk-requests.show', compact('ictHelpdeskRequest'));
    }

    // ────────────────────────────────────────────────────────────────────────
    //  ADMIN — update status + notes
    // ────────────────────────────────────────────────────────────────────────
    public function adminUpdate(Request $request, IctHelpdeskRequest $ictHelpdeskRequest)
    {
        $validated = $request->validate([
            'status'      => 'required|in:Pending,In Progress,Resolved,Closed,Cancelled',
            'admin_notes' => 'nullable|string',
        ]);

        $ictHelpdeskRequest->update([
            'status'      => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? $ictHelpdeskRequest->admin_notes,
            'resolved_at' => in_array($validated['status'], ['Resolved', 'Closed'])
                ? ($ictHelpdeskRequest->resolved_at ?? now())
                : null,
        ]);

        return back()->with('success', "Request {$ictHelpdeskRequest->ticket_no} updated to {$validated['status']}.");
    }

    // ────────────────────────────────────────────────────────────────────────
    //  ADMIN — delete
    // ────────────────────────────────────────────────────────────────────────
    public function adminDestroy(IctHelpdeskRequest $ictHelpdeskRequest)
    {
        $no = $ictHelpdeskRequest->ticket_no;
        $ictHelpdeskRequest->delete();
        return back()->with('success', "Request {$no} has been deleted.");
    }
}