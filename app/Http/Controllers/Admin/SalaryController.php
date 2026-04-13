<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Salary;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function index()
    {
        $salaryGrades = Salary::orderBy('salary_grade')->get();
        return view('admin.salary.index', compact('salaryGrades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'salary_grade' => 'required|integer|unique:tbl_salary,salary_grade',
            'step_1'       => 'required',
        ]);

        Salary::create($request->only([
            'salary_grade',
            'step_1', 'step_2', 'step_3', 'step_4',
            'step_5', 'step_6', 'step_7', 'step_8',
        ]));

        return redirect()->route('admin.salary.index')
            ->with('success', 'Salary grade added.');
    }

    public function update(Request $request, int $id)
    {
        $salary = Salary::findOrFail($id);

        $salary->update($request->only([
            'step_1', 'step_2', 'step_3', 'step_4',
            'step_5', 'step_6', 'step_7', 'step_8',
        ]));

        return redirect()->route('admin.salary.index')
            ->with('success', 'Salary grade updated.');
    }

    public function destroy(int $id)
    {
        Salary::findOrFail($id)->delete();
        return redirect()->route('admin.salary.index')
            ->with('success', 'Salary grade deleted.');
    }
}