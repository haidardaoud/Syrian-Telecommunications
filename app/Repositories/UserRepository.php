<?php

namespace App\Repositories;

use App\Models\Job_Offer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Str;

class UserRepository
{
////////////////////////////////////////////////////////////////////////////////////////////////////
public function findByUserName(string $userName)
{
    return User::where('user_name', $userName)->first();
}

public function createUser(array $data)
{
    return User::create([
        'user_name' => $data['user_name'],
        'company_data' => $data['company_data'] ?? null,
        'job_id' => $data['job_id'] ?? 1
    ]);
}






    ////////////////////////////////////////////////////////////////////////////////////////////////////////////
//     public function updatePassword(string $userName, string $newPassword)
// {
//     $user = $this->findByUserName($userName);
//     if ($user) {
//         $user->password = Hash::make($newPassword); // تشفير كلمة المرور
//         $user->save();
//         return $user;
//     }
//     return null;
// }
public function updatePasswordById(int $userId, string $newPassword)
{
    $user = User::find($userId); // البحث عبر الـ ID

    if ($user) {
        $user->password = Hash::make($newPassword);
        $user->save();
        return $user;
    }

    return null; // إذا لم يُوجد المستخدم
}
///////////////////////////////////////////////////////////////////



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


public function createTemporaryUser(): User
{
    return User::create([
        'name' => 'Temporary User',
        //'email' => 'temp_' . uniqid() . '@example.com',
        'password' => Hash::make(str()::random(16)),
        'is_temporary' => true, // تأكد أن هذا الحقل موجود في جدول المستخدمين
    ]);
}

public function deleteExpiredTemporaryUsers(): int
{
    // حذف المستخدمين المؤقتين الذين انتهت صلاحية التوكن الخاصة بهم
    $deleted = User::where('is_temporary', true)
        ->whereDoesntHave('tokens', function ($query) {
            $query->where('expires_at', '>', now());
        })
        ->delete();

    return $deleted;
}


public function suspendUser(int $userId): bool
    {
        $user = User::find($userId);
        if (!$user) {
            throw new \Exception('User not found');
        }
        $user->is_suspended = true;
        return $user->save();
    }

    public function reactivateUser(int $userId): bool
    {
        $user = User::find($userId);
        if (!$user) {
            throw new \Exception('User not found');
        }
        $user->is_suspended = false;
        return $user->save();
    }

    public function forceLogoutUser(int $userId): int
    {
        $user = User::find($userId);
        if (!$user) {
            throw new \Exception('User not found');
        }
        // حذف كل التوكنات للمستخدم (تسجيل خروجه من كل الأجهزة)
        return $user->tokens()->delete();
    }


}
