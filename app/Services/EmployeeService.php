<?php

namespace App\Services;

use App\Logs\Log;
use App\Models\Employee;
use App\Models\Resume;
use Core\Dropbox;
use Core\Mail;
use Core\Redis;

class EmployeeService
{
    protected $cache, $drive, $logs;

    public function retrieve($page, $department, $search, $cacheKey)
    {
        if ($search) {
            $employees = Employee::when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })->when($department, function ($query, $dept) {
                return $query->where('department_id', $dept);
            })->with('department', 'resume')->orderBy('id', 'desc')->paginate(2, $page);


            return [...$employees, 'dept' => $department];
        }

        $this->cache = new Redis($cacheKey);

        $cached = $this->cache->get();
        if ($cached) return $cached;

        $employees = Employee::when($department, function ($query, $dept) {
            return $query->where('department_id', $dept);
        })->with('department', 'resume')->orderBy('id', 'desc')->paginate(2, $page);

        $employees = [...$employees, 'dept' => $department];

        if (!empty($employees['data'])) {
            $this->cache->set($employees);
        }

        return $employees;
    }

    public function create($data, $cacheKey)
    {
        $this->cache = new Redis($cacheKey);
        $this->cache->delete();

        $employee = Employee::where('email', $data?->email)->first();
        if ($employee) return false;

        $employee = Employee::create([
            'name' =>  $data?->name,
            'email' => $data?->email,
            'department_id' => $data?->department_id,
            'manager' => $data?->manager,
            'hire_date' => $data?->hire_date,
        ]);

        if ($data->resume?->error !== 4) {
            $this->drive = new Dropbox;

            $resume = $this->drive->upload($data->resume);
            $this->attachResume($employee->id, $resume);
        }

        if ($employee) {
            $this->sendEmailToManager($employee);
            $this->writeLog([
                'action' => 'CREATE_EMPLOYEE',
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
            ]);
        }

        return $employee;
    }

    public function update($data, $id, $cacheKey)
    {
        $this->cache = new Redis($cacheKey);
        $this->cache->delete();

        $employee = Employee::with('resume')->find($id);
        if (!$employee) return false;

        $employee->update([
            'name' =>  $data->name,
            'email' => $data->email,
            'department_id' => $data->department_id,
            'manager' => $data->manager,
            'hire_date' => $data->hire_date,
        ]);

        if ($data->resume?->error !== 4) {
            $this->drive = new Dropbox();

            $resume = $this->drive->upload($data->resume);
            $this->detachAndReplaceResume($employee->id, $resume);
        }

        return $employee;
    }

    public function delete($id, $cacheKey)
    {
        $this->cache = new Redis($cacheKey);
        $this->cache->delete();

        $employee = Employee::with('resume')->find($id);
        if (!$employee) return false;

        if ($path = $employee->resume()->path) {
            $this->drive = new Dropbox();
            $this->drive->delete($path);
        }

        $employee->delete();
        return true;
    }

    public function sendEmailToManager($employee)
    {
        return Mail::send(
            to: $employee->manager,
            name: 'Mr. Manager',
            subject: 'A New Employee Hired',
            template: 'new-employee',
            data: [
                'employeeName' => $employee->name,
                'employeeEmail' => $employee->email,
                'employeeHireDate' => formatDate($employee->hire_date),
            ]
        );
    }

    public function attachResume($employeeId, $resume)
    {
        $created = Resume::create([
            'name' => $resume->name,
            'drive_id' => $resume->id,
            'path' => $resume->path,
            'employee_id' => $employeeId,
        ]);

        $this->writeLog([
            'action' => 'UPLOAD_DOCUMENT',
            'file_id' => $created->id,
            'file_name' => $created->name,
        ]);

        return $created ? $created : false;
    }

    public function detachAndReplaceResume($employeeId, $new)
    {
        $old = Resume::where('employee_id', $employeeId)->first();
        if (!$old) return $this->attachResume($employeeId, $new);

        $this->drive->delete($old->path);
        $updated = $old->update([
            'name' => $new->name,
            'drive_id' => $new->id,
            'path' => $new->path,
        ]);

        $this->writeLog([
            'action' => 'UPLOAD_DOCUMENT',
            'file_id' => $old->id,
            'file_name' => $new->name,
        ]);

        return $updated;
    }

    public function writeLog(array $data): bool
    {
        $this->logs = new Log('logs');
        return $this->logs->create($data);
    }
}
