<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $query = Leave::with('employee')
            ->orderBy('date_applied', 'desc');

        if ($request->filled('status')) {
            $query->where('leave_status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('fullname', 'like', '%'.$request->search.'%');
        }

        $leaves = $query->paginate(20);

        return view('admin.leaves.index', compact('leaves'));
    }

    public function approve(int $id)
    {
        Leave::findOrFail($id)->update([
            'leave_status' => 'Approved',
            'approve_by' => Auth::id(),
            'approve_date' => now()->toDateString(),
            'approve_time' => now()->toTimeString(),
        ]);

        return redirect()->route('admin.leaves.index')
            ->with('success', 'Leave approved.');
    }

    public function decline(Request $request, int $id)
    {
        $request->validate(['remarks' => 'required|string|max:50']);

        Leave::findOrFail($id)->update([
            'leave_status' => 'Declined',
            'remarks' => $request->remarks,
            'approve_by' => Auth::id(),
        ]);

        return redirect()->route('admin.leaves.index')
            ->with('success', 'Leave declined.');
    }
}
