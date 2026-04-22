<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $employee = Employee::with([
            'employment', 'education',
            'eligibility', 'user',
        ])->where('user_id', Auth::id())->first();

        $prefix = $this->getPrefix();
        $layout = $this->getLayout($prefix);

        return view('shared.profile.show', compact('employee', 'prefix', 'layout'));
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:2048',
        ]);

        $employee = Employee::where('user_id', Auth::id())->firstOrFail();
        $employee->photo_path = $request->file('photo')->store('photos', 'public');
        $employee->save();

        return redirect()->back()->with('success', 'Profile photo updated.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect.'
            ]);
        }

        $user->update([
            'password'    => Hash::make($request->password),
            'pass_change' => true,
        ]);

        return redirect()->back()->with('success', 'Password changed successfully.');
    }

    private function getPrefix(): string
    {
        return match(Auth::user()->user_pos) {
            'Super Administrator' => 'admin',
            'HR'                  => 'hr',
            default               => (\App\Models\Role::where('role_desc', Auth::user()->user_pos)->first()?->role_type === 'Department Head') ? 'head' : 'employee',
        };
    }

    private function getLayout(string $prefix): string
    {
        return match($prefix) {
            'admin' => 'layouts.admin',
            'hr'    => 'layouts.hr',
            'head'  => 'layouts.head',
            default => 'layouts.employee',
        };
    }
}