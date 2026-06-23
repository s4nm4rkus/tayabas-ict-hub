<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeProfileController extends Controller
{
    public function edit()
    {
        $employee = Employee::where('user_id', Auth::id())->firstOrFail();

        return view('employee.profile.edit', compact('employee'));
    }

    public function update(Request $request)
    {
        $employee = Employee::where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'middle_name'    => 'nullable|string|max:100',
            'ex_name'        => 'nullable|string|max:20',
            'place_of_birth' => 'nullable|string|max:100',
            'contact_num'    => 'nullable|string|max:20',
            'bp_no'          => 'nullable|string|max:50',
            'disability'     => 'nullable|string|max:100',
            'street'         => 'nullable|string|max:100',
            'street_brgy'    => 'nullable|string|max:100',
            'municipality'   => 'nullable|string|max:100',
            'province'       => 'nullable|string|max:100',
            'region'         => 'nullable|string|max:100',
        ]);

        $employee->update($request->only([
            'middle_name',
            'ex_name',
            'place_of_birth',
            'contact_num',
            'bp_no',
            'disability',
            'street',
            'street_brgy',
            'municipality',
            'province',
            'region',
        ]));

        return redirect()->route('employee.profile.show')
            ->with('success', 'Profile updated successfully.');
    }
}
