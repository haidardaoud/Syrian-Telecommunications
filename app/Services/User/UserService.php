<?php

namespace App\Services\User;

use App\Repositories\BundleRepository;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\CompanyAuthService;
use Illuminate\Support\Facades\Hash;

class UserService
{
    private $userRepository;
    private $companyAuthService;

    public function __construct(UserRepository $userRepository, CompanyAuthService $companyAuthService,private BundleRepository $bundleRepository)
    {
        $this->userRepository = $userRepository;
        $this->companyAuthService = $companyAuthService;
    }

   ///////////////////////////////////////////////////////////////////////////
   ////1- تشييك بجدول اليوزر اذا موجود والROLE_ID NOT 1
   ////2- ارجاع الى الصفحى حسب الrole id
   ////3- ارسال api للشركة والتخقق من الشركة
   ////4- اذا كان الرد  ok => يتم التأكد منه اذا كان موجودا في DB الخاصة فينا
   ////5- ارجاع المعلومات
   ////6- اذا لم يكن موجود يتم انشاء واخد مع الjob_id = 1


//    public function login(array $credentials)
// {
//     try {
//         // 1. التحقق من وجود المستخدم في DB مع job_id ≠ 1
//         $existingUser = $this->userRepository->findByUserName($credentials['user_name']);

//         if ($existingUser && $existingUser->job_id != 1) {
//             return $this->handleSpecialUser($existingUser);
//         }

//         // 2. إذا لم يكن موجود أو job_id = 1 نتحقق من API الشركة
//         $apiResponse = $this->companyAuthService->login($credentials);

//         if (!$apiResponse['success']) {
//             throw new \Exception($apiResponse['error'] ?? 'Authentication failed');
//         }

//         // 3. إنشاء/تحديث المستخدم في DB
//         $user = $existingUser ?? $this->userRepository->createUser([
//             'user_name' => $credentials['user_name'],
//             'company_data' => json_encode($apiResponse['data']),
//             'job_id' => 1 // قيمة افتراضية للمستخدمين العاديين
//         ]);

//         return response()->json([
//            // 'token' => $user->createToken('auth_token')->plainTextToken,
//             'user' => $user->only(['id', 'user_name', 'job_id']),
//             'company_data' => $apiResponse['data'],
//             //'user_type' => 'regular'
//         ]);

//     } catch (\Exception $e) {
//         Log::error("Login Error: " . $e->getMessage());
//         return response()->json([
//             'error' => $e->getMessage(),
//             'code' => 401
//         ], 401);
//     }
// }

private function handleSpecialUser($user)
{
    return response()->json([
        'token' => $user->createToken('auth_token')->plainTextToken,
        'user' => $user->only(['id', 'user_name', 'job_id']),
        'user_type' => 'special' // admin, employee, etc.
    ]);
}
/////////////////////////////////////////////////////////////////////////////////////////////////////
public function login(array $credentials)
{
    // 1. التحقق من API الشركة أولاً
    $apiResponse = $this->companyAuthService->login($credentials);

    // 2. إذا فشلت مصادقة الشركة أو كانت البيانات null
    if (!$apiResponse['success'] || empty($apiResponse['data'])) {
        return response()->json([
            'error' => 'معلومات الدخول غير صحيحة',
            'message' => 'اسم المستخدم أو كلمة المرور غير صحيحة',
            'code' => 401
        ], 401);
    }

    // 3. فقط إذا نجح التحقق مع الشركة، نتحقق من المستخدم المحلي
    $existingUser = $this->userRepository->findByUserName($credentials['user_name']);

    // 4. إذا كان مستخدم خاص (job_id != 1)
    if ($existingUser && $existingUser->job_id != 1) {
        return $this->handleSpecialUser($existingUser);
    }

    // 5. إذا كان مستخدم عادي (أو غير موجود)
    $user = $existingUser ?? $this->userRepository->createUser([
        'user_name' => $credentials['user_name'],
        'company_data' => json_encode($apiResponse['data']),
        'job_id' => 1
    ]);

    return response()->json([
        'user' => $user->only(['id', 'user_name', 'job_id']),
        'company_data' => $apiResponse['data']
    ]);
}


public function getAvailableBundles(string $username, string $password): array
{
    return $this->bundleRepository->getBundles($username, $password);
}

// app/Services/BillService.php

// app/Services/BillService.php
// app/Services/BillService.php
public function fetchCustomerBills(string $phoneNumber): array
{
    $result = $this->bundleRepository->getCustomerBills($phoneNumber);

    if (!$result['success']) {
        return [
            'success' => false,
            'error' => $result['error'],
            'code' => $result['code']
        ];
    }

    return [
        'success' => true,
        'data' => $result['data']
    ];
}




public function getSubscriberInfo(
    string $username,
    string $password,
    string $startTime,
    string $endTime
): ?array {
    return $this->companyAuthService->fetchSubscriberData(
        $username,
        $password,
        $startTime,
        $endTime
    );
}

////////////////////////////////////////////////////////////////////////////////////////////

public function getSubscriberPackageInfo(string $username, string $password): ?array
{
    return $this->companyAuthService->fetchSubscriberPackages($username, $password);
}

/////////////////////////////////////////////////////////////////////////////////////////////////////

    //ظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظ
//     public function resetPassword(array $data)
// {
//     $apiResponse = $this->companyAuthService->resetPassword($data);

//     if ($apiResponse['success']) {
//         $externalUser = $apiResponse['data']['user'];
//         $user = $this->userRepository->updatePassword($externalUser['user_name'], $data['new_password']);

//         if ($user) {
//             return response()->json(['message' => 'Password reset successful']);
//         }
//     }

//     return response()->json($apiResponse['error'], $apiResponse['code']);
// }




/////////////////////////////////////////////////////////////////////////////z/z/z/z/z/z/zzz/z//z/z/z/
// public function changePassword(array $data) // الآن تقبل مصفوفة واحدة
// {
//     $userId = auth()->id(); // أو يمكن تمرير الـ ID من الـ Controller

//     $verifyResponse = $this->companyAuthService->verifyCredentials([
//         'user_id'   => $userId,
//         'password'  => $data['current_password']
//     ]);

//     if (!$verifyResponse['success']) {
//         return response()->json(['error' => 'Current password is incorrect'], 401);
//     }

//     $this->userRepository->updatePasswordById($userId, $data['new_password']);

//     return response()->json(['message' => 'Password changed successfully']);
// }
// /////////////////////////////////////////////////////////////////////////////////////

// app/Services/UserService.php
// public function changePassword(array $data)
// {
//     // 1. التحقق من CAPTCHA (تلقائي عبر الـ Request)
//     // 2. التحقق من تطابق كلمة المرور الجديدة (تلقائي عبر 'confirmed')

//     // 3. التحقق من كلمة السر القديمة عبر API الشركة
//     $verifyResponse = $this->companyAuthService->verifyCredentials([
//         'user_id'   => auth()->id(),
//         'password'  => $data['current_password']
//     ]);

//     if (!$verifyResponse['success']) {
//         return response()->json([
//             'error' => 'Current password is incorrect',
//             'new_captcha' => captcha_src() // إعادة توليد CAPTCHA عند الفشل
//         ], 401);
//     }

//     // 4. تحديث كلمة السر
//     $this->userRepository->updatePasswordById(auth()->id(), $data['new_password']);

//     return response()->json([
//         'message' => 'Password changed successfully',
//         'captcha_refreshed' => false
//     ]);
// }

////////////////////////////////////////////////////////////////////





public function impersonateAsTemporaryUser(int $adminId)
{
    try {
        $admin = User::find($adminId);

        // 1. إنشاء مستخدم مؤقت
        $tempUser = $this->userRepository->createTemporaryUser();

        // 2. إنشاء توكن مؤقت لمدة 30 دقيقة
        $token = $tempUser->createToken('temp-impersonation-token', ['*'], now()->addMinutes(30))->plainTextToken;

        // 3. تسجيل العملية
        Log::info("Admin {$admin->name} impersonated a temporary user (ID: {$tempUser->id})");

        return [
            'success' => true,
            'token' => $token,
            'user' => $tempUser,
            'message' => 'Temporary impersonation successful',
        ];
    } catch (\Exception $e) {
        return [
            'success' => false,
            'error' => $e->getMessage(),
            'message' => 'Temporary impersonation failed',
        ];
    }

}
    public function cleanTemporaryUsers(): array
    {
        try {
            $deletedCount = $this->userRepository->deleteExpiredTemporaryUsers();

            Log::info("Deleted {$deletedCount} expired temporary users");

            return [
                'success' => true,
                'message' => "{$deletedCount} temporary users deleted successfully.",
                'count' => $deletedCount
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to clean temporary users.'
            ];
        }
    }

    public function suspendUser(int $userId): array
    {
        try {
            $this->userRepository->suspendUser($userId);

            Log::info("User {$userId} suspended by admin.");

            return ['success' => true, 'message' => "User {$userId} suspended successfully."];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage(), 'message' => 'Suspend failed'];
        }
    }

    public function reactivateUser(int $userId): array
    {
        try {
            $this->userRepository->reactivateUser($userId);

            Log::info("User {$userId} reactivated by admin.");

            return ['success' => true, 'message' => "User {$userId} reactivated successfully."];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage(), 'message' => 'Reactivate failed'];
        }
    }

    public function forceLogoutUser(int $userId): array
    {
        try {
            $deletedTokens = $this->userRepository->forceLogoutUser($userId);

            Log::info("User {$userId} force logged out by admin. Tokens deleted: {$deletedTokens}");

            return ['success' => true, 'message' => "User {$userId} logged out from all sessions."];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage(), 'message' => 'Force logout failed'];
        }
    }

}

// class UserService
// {
//     private $userRepository;

//     public function __construct(UserRepository $userRepository)
//     {
//         $this->userRepository = $userRepository;
//     }

//     public function login(array $credentials)
//     {
//         $response = $this->sendToCompanyAPI($credentials);

//         // تحقق أن الرد ناجح
//         if ($response->getStatusCode() === 200) {
//             $responseData = $response->getData(true); // تحويل JSON إلى مصفوفة

//             // التحقق من أن المستخدم موجود في رد الشركة
//             if (isset($responseData['status']) && $responseData['status'] === true) {
//                 $externalUser = $responseData['user'];

//                 // أنشئ المستخدم محلياً إذا لم يكن موجود (أو فقط التحقق منه)
//                 $user = User::firstOrCreate(
//                     //يجب الاتفاق بيننا وبين الشركة على id محدد دائما يشير الى زبون
//                     //['job_id'=> $externalUser['id']],
//                     ['user_name' => $externalUser['user_name']],
//                     [
//                         // ضع كلمة مرور وهمية إذا كنت لا تستخدمها محلياً
//                         'password' => Hash::make($credentials['password']),
//                     ]
//                 );

//                 // إنشاء التوكن
//                 $token = $user->createToken('auth_token')->plainTextToken;

//                 // إعادة التوكن في الرد
//                 return response()->json([
//                     'message' => 'Login successful',
//                     'token' => $token,
//                     'redirect_to' => 'page null',
//                     'user' => $user,
//                 ]);
//             }
//         }


//         return $response;
//     }



//     private function sendToCompanyAPI(array $credentials)
// {
//     $client = new \GuzzleHttp\Client();
//     /*      اذا اردنا اضافة timeout للرد
//     $client = new \GuzzleHttp\Client([
//     'timeout' => 5, // بالثواني: يعني انتظر بحد أقصى 5 ثوانٍ
// ]);
// */

//     try {
//         $response = $client->post('https://api.company.com/login-check', [
//             'json' => $credentials,
//             'headers' => [
//                 'Accept' => 'application/json',
//                 // 'Authorization' => 'Bearer YOUR_API_KEY', // إذا كان مطلوب
//             ],
//         ]);

//         $body = json_decode($response->getBody(), true);

//         return response()->json($body, $response->getStatusCode());

//     } catch (\GuzzleHttp\Exception\RequestException $e) {
//         $status = $e->getResponse() ? $e->getResponse()->getStatusCode() : 500;
//         $message = $e->getResponse() ? json_decode($e->getResponse()->getBody(), true) : ['error' => 'Company API error'];

//         return response()->json($message, $status);
//     }
// }

// }
