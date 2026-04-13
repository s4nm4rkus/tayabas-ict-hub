<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $inbox = Message::where('receiver', $user->username)
                        ->whereNotIn('mes_status', ['Deleted', 'DeletedBySender'])
                        ->orderBy('date_time', 'desc')
                        ->get();

        $sent = Message::where('userid', $user->username)
                       ->whereNotIn('mes_status', ['DeletedBySender', 'Deleted'])
                       ->orderBy('date_time', 'desc')
                       ->get();

        // Mark all unread as read
        Message::where('receiver', $user->username)
               ->where('mes_status', 'Unread')
               ->update(['mes_status' => 'Read']);

        return view('shared.messages.index', compact('inbox', 'sent'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver'    => 'required|email|exists:users,username',
            'subject_mes' => 'required|string|max:100',
            'messages'    => 'required|string|max:1000',
        ]);

        Message::create([
            'userid'      => Auth::user()->username,
            'receiver'    => $request->receiver,
            'messages'    => $request->messages,
            'subject_mes' => $request->subject_mes,
            'date_time'   => now(),
            'mes_status'  => 'Unread',
        ]);

        return redirect()->route(
            $this->routePrefix() . '.messages.index'
        )->with('success', 'Message sent successfully.');
    }

    public function destroy(int $id)
    {
        $user    = Auth::user();
        $message = Message::findOrFail($id);

        if ($message->userid === $user->username) {
            $message->update(['mes_status' => 'DeletedBySender']);
        } elseif ($message->receiver === $user->username) {
            $message->update(['mes_status' => 'Deleted']);
        }

        return redirect()->route(
            $this->routePrefix() . '.messages.index'
        )->with('success', 'Message deleted.');
    }

    private function routePrefix(): string
    {
        return match(Auth::user()->user_pos) {
            'Super Administrator' => 'admin',
            'HR'                  => 'hr',
            default               => $this->getDeptHeadOrEmployee(),
        };
    }

    private function getDeptHeadOrEmployee(): string
    {
        $role = \App\Models\Role::where('role_desc', Auth::user()->user_pos)->first();
        return $role?->role_type === 'Department Head' ? 'head' : 'employee';
    }
}