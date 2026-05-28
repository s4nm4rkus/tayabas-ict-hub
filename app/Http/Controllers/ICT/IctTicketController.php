<?php

namespace App\Http\Controllers\ICT;

use App\Http\Controllers\Controller;
use App\Models\IctTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IctTicketController extends Controller
{
    // ────────────────────────────────────────────────────────────────────────
    //  SUBMIT (POST /ict/ta-form)
    //  Called by the TA-form blade after JS confirm modal
    // ────────────────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:200',
            'position'          => 'required|string|max:200',
            'department'        => 'required|string|max:150',
            'date'              => 'required|date',
            'selectedOptions'   => 'required|array|min:1',
            'selectedOptions.*' => 'string',
            'others_text'       => 'nullable|string|max:255',
            'description'       => 'required|string',
        ]);

        // If "Others" is checked, others_text becomes required
        if (in_array('Others', $validated['selectedOptions'])) {
            $request->validate([
                'others_text' => 'required|string|max:255',
            ]);
        }

        IctTicket::create([
            'ticket_no'        => IctTicket::generateTicketNo(),
            'user_id'          => Auth::id(),
            'full_name'        => $validated['name'],
            'position'         => $validated['position'],
            'department'       => $validated['department'],
            'date_reported'    => $validated['date'],
            'assistance_types' => $validated['selectedOptions'],
            'others_text'      => $validated['others_text'] ?? null,
            'description'      => $validated['description'],
            'status'           => 'Pending',
        ]);

        return redirect()
            ->route('ict.ta-form')
            ->with('success', 'Your ticket has been submitted successfully. The ICT Unit will respond within 24 hours.');
    }

    // ────────────────────────────────────────────────────────────────────────
    //  MY TICKETS — logged-in user sees their own submissions
    //  GET /ict/my-tickets
    // ────────────────────────────────────────────────────────────────────────

    public function myTickets()
    {
        $tickets = IctTicket::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('ict.my-tickets', compact('tickets'));
    }

    // ────────────────────────────────────────────────────────────────────────
    //  ADMIN — list all tickets (Super Administrator only)
    //  GET /admin/ict-tickets
    // ────────────────────────────────────────────────────────────────────────

    public function adminIndex(Request $request)
    {
        $query = IctTicket::with(['user.employee'])
            ->orderByDesc('created_at');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        // Search by ticket number or name
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('ticket_no', 'like', "%{$s}%")
                  ->orWhere('full_name', 'like', "%{$s}%")
                  ->orWhere('department', 'like', "%{$s}%");
            });
        }

        $tickets = $query->paginate(15)->withQueryString();

        // Counts for dashboard summary cards
        $counts = [
            'all'         => IctTicket::count(),
            'pending'     => IctTicket::where('status', 'Pending')->count(),
            'in_progress' => IctTicket::where('status', 'In Progress')->count(),
            'resolved'    => IctTicket::where('status', 'Resolved')->count(),
        ];

        return view('ict.admin.ta-requests.index', compact('tickets', 'counts'));
    }

    // ────────────────────────────────────────────────────────────────────────
    //  ADMIN — show single ticket
    //  GET /admin/ict-tickets/{id}
    // ────────────────────────────────────────────────────────────────────────

    public function adminShow(IctTicket $ictTicket)
    {
        $ictTicket->load('user.employee');
        return view('ict.admin.ta-requests.show', compact('ictTicket'));
    }

    // ────────────────────────────────────────────────────────────────────────
    //  ADMIN — update status + notes
    //  PUT /admin/ict-tickets/{id}
    // ────────────────────────────────────────────────────────────────────────

    public function adminUpdate(Request $request, IctTicket $ictTicket)
    {
        $validated = $request->validate([
            'status'      => 'required|in:Pending,In Progress,Resolved,Closed,Cancelled',
            'admin_notes' => 'nullable|string',
        ]);

        $ictTicket->update([
            'status'      => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? $ictTicket->admin_notes,
            'resolved_at' => in_array($validated['status'], ['Resolved', 'Closed'])
                ? ($ictTicket->resolved_at ?? now())
                : null,
        ]);

        return back()->with('success', "Ticket {$ictTicket->ticket_no} updated to {$validated['status']}.");
    }

    // ────────────────────────────────────────────────────────────────────────
    //  ADMIN — delete ticket
    //  DELETE /admin/ict-tickets/{id}
    // ────────────────────────────────────────────────────────────────────────

    public function adminDestroy(IctTicket $ictTicket)
    {
        $no = $ictTicket->ticket_no;
        $ictTicket->delete();
        return back()->with('success', "Ticket {$no} has been deleted.");
    }
}
