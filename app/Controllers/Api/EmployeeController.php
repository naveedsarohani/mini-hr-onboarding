<?php

namespace App\Controllers\Api;

use App\Services\EmployeeService;
use Core\Request;

class EmployeeController
{
    public function index(Request $request, EmployeeService $service)
    {
        $page = $request?->page ?? 1;
        $department = $request?->department ?? null;
        $search = $request?->search ?? null;

        $cacheKey = cache_key(compact('page', 'department'), 'employees');
        $employees = $service->retrieve($page, $department, $search, $cacheKey);

        return view('components.employees.listing', compact('employees'));
    }
}
