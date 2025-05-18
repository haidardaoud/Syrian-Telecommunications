<?php

namespace App\Repositories;

use App\Models\Job_Offer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class EmployeeRepository
{
public function createEmployee(array $userData, array $jobData)
{
    // 1. إنشاء عرض الوظيفة أولاً
    $jobOffer = Job_Offer::create([
        'salary' => $jobData['salary'],
        'position' => $jobData['position']
    ]);

    // 2. إنشاء المستخدم مع ربطه بالـ job_id
    $user = User::create([
        'name' => $userData['name'],
        // 'email' => $userData['email'],
        // 'password' => Hash::make($userData['password']),
        // 'role' => 'employee',
        'job_id' => $jobOffer->id // الربط هنا
    ]);

    return [
        'user' => $user,
        'job_offer' => $jobOffer
    ];
}

// app/Repositories/EmployeeRepository.php
public function updateJobDetails(int $jobId, array $jobData)
{
    $jobOffer = Job_Offer::find($jobId);

    if (!$jobOffer) {
        throw new \Exception('Job offer not found');
    }

    $jobOffer->update($jobData);
    return $jobOffer;
}



public function deleteEmployee(int $userId)
{
    $user = User::find($userId);
    if (!$user) {
        throw new \Exception('User not found');
    }

    // حذف عرض الوظيفة أولاً (إذا كان CASCADE غير مفعل)
    if ($user->job_id) {
        Job_Offer::where('id', $user->job_id)->delete();
    }

    $user->delete();
    return true;
}
// app/Repositories/EmployeeRepository.php
public function getEmployeesByPosition(string $position)
{
    return User::whereHas('jobOffer', function ($query) use ($position) {
                $query->where('position', $position);
              })
              ->with('jobOffer')
              ->where('role', 'employee')
              ->get();
}
// app/Repositories/EmployeeRepository.php
public function getEmployeesByName(string $name)
{
    return User::with('jobOffer')
              ->where('position', 'employee')
              ->where('name', 'LIKE', "%{$name}%")
              ->get();
}
}
