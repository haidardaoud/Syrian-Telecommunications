<?php

namespace App\Services\User;

use App\Models\User;
use App\Repositories\EmployeeRepository;
use App\Services\CompanyAuthService;

class EmployeeService
{
    private $EmployeeRepository;
    private $companyAuthService;

    public function __construct(EmployeeRepository $employeeRepository, CompanyAuthService $companyAuthService)
    {
        $this->EmployeeRepository = $employeeRepository;
        $this->companyAuthService = $companyAuthService;
    }
    public function addEmployee(array $userData, array $jobData)
{
    try {
        $employee = $this->EmployeeRepository->createEmployee($userData, $jobData);

        return [
            'success' => true,
            'data' => $employee,
            'message' => 'Employee added successfully'
        ];

    } catch (\Exception $e) {
        return [
            'success' => false,
            'error' => $e->getMessage(),
            'message' => 'Failed to add employee'
        ];
    }
}

// app/Services/EmployeeService.php
public function updateJobDetails(int $employeeId, array $jobData)
{
    try {
        $user = User::find($employeeId);
        if (!$user || !$user->job_id) {
            throw new \Exception('Employee or job details not found');
        }

        $updatedJob = $this->EmployeeRepository->updateJobDetails($user->job_id, $jobData);

        return [
            'success' => true,
            'data' => $updatedJob,
            'message' => 'Job details updated successfully'
        ];

    } catch (\Exception $e) {
        return [
            'success' => false,
            'error' => $e->getMessage(),
            'message' => 'Failed to update job details'
        ];
    }
}


// app/Services/EmployeeService.php
public function deleteEmployee(int $employeeId)
{
    try {
        $deleted = $this->EmployeeRepository->deleteEmployee($employeeId);

        return [
            'success' => $deleted,
            'message' => $deleted ? 'Employee deleted successfully' : 'Failed to delete employee'
        ];

    } catch (\Exception $e) {
        return [
            'success' => false,
            'error' => $e->getMessage(),
            'message' => 'Failed to delete employee'
        ];
    }
}

// app/Services/EmployeeService.php
public function getEmployeesByPosition(string $position)
{
    try {
        $employees = $this->EmployeeRepository->getEmployeesByPosition($position);

        return [
            'success' => true,
            'data' => $employees,
            'message' => 'Employees retrieved by position successfully'
        ];
    } catch (\Exception $e) {
        return [
            'success' => false,
            'error' => $e->getMessage(),
            'message' => 'Failed to retrieve employees by position'
        ];
    }
}

// app/Services/EmployeeService.php
public function getEmployeesByName(string $name)
{
    try {
        $employees = $this->EmployeeRepository->getEmployeesByName($name);

        return [
            'success' => true,
            'data' => $employees,
            'message' => 'Employees retrieved by name successfully'
        ];
    } catch (\Exception $e) {
        return [
            'success' => false,
            'error' => $e->getMessage(),
            'message' => 'Failed to retrieve employees by name'
        ];
    }
}

}
