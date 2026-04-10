<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('role_cat')->orderBy('role_desc')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_desc' => 'required|string|max:100|unique:tbl_role,role_desc',
            'role_cat'  => 'required|in:Teaching,Non-Teaching',
            'role_type' => 'required|in:Department Head,Employee',
        ]);

        Role::create([
            'role_desc' => $request->role_desc,
            'role_cat'  => $request->role_cat,
            'role_type' => $request->role_type,
            'role_head' => $request->role_head,
        ]);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role added successfully.');
    }

    public function update(Request $request, int $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'role_desc' => 'required|string|max:100|unique:tbl_role,role_desc,' . $id . ',role_id',
            'role_cat'  => 'required|in:Teaching,Non-Teaching',
            'role_type' => 'required|in:Department Head,Employee',
        ]);

        $role->update([
            'role_desc' => $request->role_desc,
            'role_cat'  => $request->role_cat,
            'role_type' => $request->role_type,
            'role_head' => $request->role_head,
        ]);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    public function destroy(int $id)
    {
        $role = Role::findOrFail($id);

        // Check if role is in use
        $inUse = \App\Models\User::where('user_pos', $role->role_desc)->exists();
        if ($inUse) {
            return redirect()->route('admin.roles.index')
                ->with('error', "Cannot delete — role is assigned to existing employees.");
        }

        $role->delete();
        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted.');
    }
}