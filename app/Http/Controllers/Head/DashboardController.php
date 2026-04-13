<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\Board;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $pendingLeaves = Leave::where('leave_status', 'Pending Head')->count();
        $endorsedToday = Leave::where('leave_status', 'Pending HR')
                              ->where('dept_head', Auth::id())
                              ->whereDate('updated_at', today())->count();
        $announcements = Board::with('user')
                              ->orderBy('date_time', 'desc')
                              ->take(3)->get();

        return view('head.dashboard', compact(
            'pendingLeaves', 'endorsedToday', 'announcements'
        ));
    }
}