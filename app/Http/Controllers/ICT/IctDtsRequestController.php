<?php

namespace App\Http\Controllers\ICT;

use App\Http\Controllers\Controller;
use App\Models\IctDtsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IctDtsRequestController extends Controller
{
    // ── Request type → conditional validation rules ─────────────────────────
    private function conditionalRules(string $type): array
    {
        return match ($type) {
            'Retrieve'               => ['unit_name' => 'required|string|max:150', 'reason' => 'nullable|string|max:500'],
            'Edit Document Title'    => ['new_title' => 'required|string|max:300', 'edit_reason' => 'nullable|string|max:500'],
            'Cancel Transaction'     => ['cancel_reason' => 'required|string|max:500'],
            'Reset Password'         => ['email_address' => 'required|email|max:200'],
            'Add Document'           => ['document_type' => 'required|string|max:200', 'process_days' => 'required|integer|min:1'],
            'New User Email Address' => ['new_email' => 'required|email|max:200'],
            default                  => [],
        };
    }

    // ────────────────────────────────────────────────────────────────────────
    //  SUBMIT  POST /ict/dts-form
    // ────────────────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $base = $request->validate([
            'date'           => 'required|date',
            'dts_number'     => 'required|string|max:100',
            'requester_name' => 'required|string|max:200',
            'mobile_number'  => 'required|digits:11',
            'school'         => 'required|string|max:200',
            'request_type'   => 'required|in:Retrieve,Edit Document Title,Cancel Transaction,Reset Password,Add Document,New User Email Address',
        ]);

        // Validate conditional fields based on selected request type
        $conditional = $request->validate(
            $this->conditionalRules($base['request_type'])
        );

        IctDtsRequest::create([
            'ticket_no'      => IctDtsRequest::generateTicketNo(),
            'user_id'        => Auth::id(),
            'date_reported'  => $base['date'],
            'dts_number'     => $base['dts_number'],
            'requester_name' => $base['requester_name'],
            'mobile_number'  => $base['mobile_number'],
            'school'         => $base['school'],
            'request_type'   => $base['request_type'],
            // conditional — null if not relevant
            'unit_name'      => $conditional['unit_name']    ?? null,
            'reason'         => $conditional['reason']       ?? null,
            'new_title'      => $conditional['new_title']    ?? null,
            'edit_reason'    => $conditional['edit_reason']  ?? null,
            'cancel_reason'  => $conditional['cancel_reason'] ?? null,
            'email_address'  => $conditional['email_address'] ?? null,
            'document_type'  => $conditional['document_type'] ?? null,
            'process_days'   => $conditional['process_days'] ?? null,
            'new_email'      => $conditional['new_email']    ?? null,
            'status'         => 'Pending',
        ]);

        return redirect()
            ->route('ict.dts-form')
            ->with('success', 'Your DTS request has been submitted. The ICT Unit will respond within 24 hours.');
    }

    // ────────────────────────────────────────────────────────────────────────
    //  ADMIN — list all DTS requests
    // ────────────────────────────────────────────────────────────────────────
    public function adminIndex(Request $request)
    {
        $query = IctDtsRequest::with('user')
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
                  ->orWhere('requester_name', 'like', "%{$s}%")
                  ->orWhere('dts_number', 'like', "%{$s}%")
                  ->orWhere('school', 'like', "%{$s}%");
            });
        }

        $tickets = $query->paginate(15)->withQueryString();

        $counts = [
            'all'         => IctDtsRequest::count(),
            'pending'     => IctDtsRequest::where('status', 'Pending')->count(),
            'in_progress' => IctDtsRequest::where('status', 'In Progress')->count(),
            'resolved'    => IctDtsRequest::where('status', 'Resolved')->count(),
        ];

        return view('ict.admin.dts-requests.index', compact('tickets', 'counts'));
    }

    // ────────────────────────────────────────────────────────────────────────
    //  ADMIN — show single request
    // ────────────────────────────────────────────────────────────────────────
    public function adminShow(IctDtsRequest $ictDtsRequest)
    {
        $ictDtsRequest->load('user.employee');
        return view('ict.admin.dts-requests.show', compact('ictDtsRequest'));
    }

    // ────────────────────────────────────────────────────────────────────────
    //  ADMIN — update status + notes
    // ────────────────────────────────────────────────────────────────────────
    public function adminUpdate(Request $request, IctDtsRequest $ictDtsRequest)
    {
        $validated = $request->validate([
            'status'      => 'required|in:Pending,In Progress,Resolved,Closed,Cancelled',
            'admin_notes' => 'nullable|string',
        ]);

        $ictDtsRequest->update([
            'status'      => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? $ictDtsRequest->admin_notes,
            'resolved_at' => in_array($validated['status'], ['Resolved', 'Closed'])
                ? ($ictDtsRequest->resolved_at ?? now())
                : null,
        ]);

        return back()->with('success', "Request {$ictDtsRequest->ticket_no} updated to {$validated['status']}.");
    }

    // ────────────────────────────────────────────────────────────────────────
    //  ADMIN — delete
    // ────────────────────────────────────────────────────────────────────────
    public function adminDestroy(IctDtsRequest $ictDtsRequest)
    {
        $no = $ictDtsRequest->ticket_no;
        $ictDtsRequest->delete();
        return back()->with('success', "Request {$no} has been deleted.");
    }
}