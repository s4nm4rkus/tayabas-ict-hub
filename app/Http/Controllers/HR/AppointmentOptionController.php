<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\AppointmentOption;
use Illuminate\Http\Request;

class AppointmentOptionController extends Controller
{
    public function index()
    {
        $natureOptions = AppointmentOption::where('option_type', 'nature_appoint')
            ->orderBy('sort_order')->get();

        $statusOptions = AppointmentOption::where('option_type', 'status_appoint')
            ->orderBy('sort_order')->get();

        return view('hr.appointment-options.index', compact('natureOptions', 'statusOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'option_type'  => 'required|in:nature_appoint,status_appoint',
            'option_value' => 'required|string|max:100|alpha_dash',
            'option_label' => 'required|string|max:150',
            'sort_order'   => 'nullable|integer|min:0',
        ]);

        $exists = AppointmentOption::where('option_type', $request->option_type)
            ->where('option_value', strtoupper($request->option_value))
            ->exists();

        if ($exists) {
            return back()->with('error', 'That value already exists for this type.')->withInput();
        }

        AppointmentOption::create([
            'option_type'  => $request->option_type,
            'option_value' => strtoupper($request->option_value),
            'option_label' => $request->option_label,
            'sort_order'   => $request->sort_order ?? 0,
            'is_active'    => true,
        ]);

        return back()->with('success', 'Option added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'option_label' => 'required|string|max:150',
            'sort_order'   => 'nullable|integer|min:0',
            'is_active'    => 'nullable|boolean',
        ]);

        $option = AppointmentOption::findOrFail($id);
        $option->update([
            'option_label' => $request->option_label,
            'sort_order'   => $request->sort_order ?? $option->sort_order,
            'is_active'    => $request->has('is_active') ? (bool) $request->is_active : $option->is_active,
        ]);

        return back()->with('success', 'Option updated successfully.');
    }

    public function destroy($id)
    {
        $option = AppointmentOption::findOrFail($id);
        $option->delete();

        return back()->with('success', 'Option deleted successfully.');
    }

    public function toggleActive($id)
    {
        $option = AppointmentOption::findOrFail($id);
        $option->update(['is_active' => !$option->is_active]);

        return back()->with('success', 'Option status updated.');
    }
}
