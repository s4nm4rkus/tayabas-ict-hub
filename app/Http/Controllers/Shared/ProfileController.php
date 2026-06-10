<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $employee = Employee::with([
            'employment', 'education',
            'eligibility', 'user',
        ])->where('user_id', Auth::id())->first();

        $prefix = $this->routePrefix();
        $layout = $this->getLayout($prefix);

        return view('shared.profile.show', compact('employee', 'prefix', 'layout'));
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:2048',
        ]);

        $employee = Employee::where('user_id', Auth::id())->firstOrFail();

        // Delete old photo if exists
        if ($employee->photo_path && Storage::disk('public')->exists($employee->photo_path)) {
            Storage::disk('public')->delete($employee->photo_path);
        }

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

        /** @var User $user */
        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update([
            'password'    => Hash::make($request->password),
            'pass_change' => true,
        ]);

        return redirect()->back()->with('success', 'Password changed successfully.');
    }

    public function updateSignature(Request $request)
    {
        $request->validate([
            'e_signature' => 'required|image|mimes:png|max:1024',
        ]);

        /** @var User $user */
        $user = Auth::user();

        if ($user->e_signature && Storage::disk('public')->exists($user->e_signature)) {
            Storage::disk('public')->delete($user->e_signature);
        }

        $path = $request->file('e_signature')->store('signatures', 'public');
        $user->update(['e_signature' => $path]);

        return redirect()->back()->with('success', 'E-Signature updated successfully.');
    }

    private function routePrefix(): string
    {
        // Step 1: exact match
        $exact = match (Auth::user()->user_pos) {
            'Super Administrator'    => 'admin',
            'HR'                     => 'hr',
            'Administrative Officer' => 'ao',
            'ASDS'                   => 'asds',
            'Department Head'        => 'head',
            default                  => null,
        };

        if ($exact) {
            return $exact;
        }

        // Step 2: role_type lookup
        $roleType = \App\Models\Role::where('role_desc', Auth::user()->user_pos)
                        ->value('role_type');

        return $roleType === 'Department Head' ? 'head' : 'employee';
    }

    private function getLayout(string $prefix): string
    {
        return match ($prefix) {
            'admin'  => 'layouts.admin',
            'hr'     => 'layouts.hr',
            'head'   => 'layouts.head',
            'ao'     => 'layouts.ao',
            'asds'   => 'layouts.asds',
            default  => 'layouts.employee',
        };
    }
}
