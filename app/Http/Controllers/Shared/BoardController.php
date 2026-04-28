<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoardController extends Controller
{
    public function index()
    {
        $posts = Board::with('user')
            ->orderBy('date_time', 'desc')
            ->paginate(10);

        $canPost = in_array(Auth::user()->user_pos, [
            'Super Administrator', 'HR',
        ]);

        return view('shared.board.index', compact('posts', 'canPost'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:1000',
        ]);

        Board::create([
            'user_id' => Auth::id(),
            'role' => Auth::user()->user_pos,
            'title' => $request->title,
            'description' => $request->description,
            'date_time' => now(),
        ]);

        return redirect()->route(
            $this->routePrefix().'.board.index'
        )->with('success', 'Post published successfully.');
    }

    public function destroy(int $id)
    {
        $post = Board::findOrFail($id);

        // Only allow delete if owner or Super Admin
        if ($post->user_id !== Auth::id() &&
            Auth::user()->user_pos !== 'Super Administrator') {
            abort(403);
        }

        $post->delete();

        return redirect()->route(
            $this->routePrefix().'.board.index'
        )->with('success', 'Post deleted.');
    }

    private function routePrefix(): string
    {
        return match (Auth::user()->user_pos) {
            'Super Administrator' => 'admin',
            'HR' => 'hr',
            default => $this->getDeptHeadOrEmployee(),
        };
    }

    private function getDeptHeadOrEmployee(): string
    {
        $role = Role::where('role_desc', Auth::user()->user_pos)->first();

        return $role?->role_type === 'Department Head' ? 'head' : 'employee';
    }
}
