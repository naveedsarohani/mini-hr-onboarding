<?php

namespace App\Controllers;

use App\Logs\Log;
use App\Models\Department;
use App\Models\Employee;
use App\Services\EmployeeService;
use Core\Request;

class EmployeeController
{
    public function index(Request $request)
    {
        $departments = Department::all();
        return view('pages.protected.employees.index', compact('departments'));
    }

    public function show(Request $request, $id)
    {
        $employee = Employee::with('department', 'resume')->find($id);
        if (!$employee) return redirect()->back()->withToast('error', 'Invalid employees ID.');

        return view('pages.protected.employees.show', compact('employee'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('pages.protected.employees.create', compact('departments'));
    }

    public function store(Request $request, EmployeeService $service)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'department_id' => 'required|numeric',
            'manager' => 'required|email',
            'hire_date' => 'required|date',
            'resume' => 'nullable|mimes:pdf,word|size:100',
        ]);

        $page = $request?->page ?? 1;
        $department = $request?->department ?? null;

        $cacheKey = cache_key(compact('page', 'department'), 'employees');

        if (!$service->create($validated, $cacheKey)) {
            $error = ['email' => "The '{$validated->email}'  is already taken."];
            return redirect()->back()->with('form-errors', $error)->withInputs();
        }

        return redirect()->route('employees.index')->withToast('success', 'The employee details have been created.');
    }

    public function edit(Request $request, $id)
    {
        $employee = Employee::with('department', 'resume')->find($id);
        $departments = Department::all();

        return view('pages.protected.employees.edit', compact('employee', 'departments'));
    }

    public function update(Request $request, EmployeeService $service, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'department_id' => 'required|numberic',
            'manager' => 'required|email',
            'hire_date' => 'required|date',
            'resume' => 'nullable|mimes:pdf,word|size:100',
        ]);

        $page = $request?->page ?? 1;
        $department = $request?->department ?? null;

        $cacheKey = cache_key(compact('page', 'department'), 'employees');

        if (!$service->update($validated, $id, $cacheKey)) {
            return redirect()->back()->withToast('error', 'Invalid employees ID.')->withInputs();
        }

        return redirect()->route('employees.index')->withToast('success', 'The employee details have been updated.');
    }

    public function destroy(Request $request, $id, EmployeeService $service)
    {
        $page = $request?->page ?? 1;
        $department = $request?->department ?? null;

        $cacheKey = cache_key(compact('page', 'department'), 'employees');

        if (!$service->delete($id, $cacheKey)) {
            return redirect()->back()->withToast('error', 'Invalid employees ID.');
        }

        return redirect()->route('employees.index')->withToast('success', 'The employee details have been deleted.');
    }
}
