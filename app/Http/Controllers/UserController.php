<?php

namespace App\Http\Controllers;

use App\Http\Requests\BillRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UsageLogRequest;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreEmployeeRequest;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Services\User\UserService;
use Carbon\Carbon;

class UserController extends Controller
{
        private $userService;

        /**
         * Inject UserService into the Controller.
         *
         * @param UserService $userService
         */
        public function __construct(UserService $userService)
        {
            $this->userService = $userService;
        }

        /**
         * Handle the login request.
         *
         * @param Request $request
         * @return \Illuminate\Http\JsonResponse
         */

        public function login(LoginRequest $request)
{
    return $this->userService->login($request->credentials());
}


public function index(LoginRequest $request)
{
    try {
        $bundles = $this->userService->getAvailableBundles(
            $request->input('userName'),
            $request->input('userPswd')
        );

        return response()->json([
            'success' => true,
            'basic' => $bundles['basic'],
            'bundles' => $bundles['bundles']
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to retrieve bundles',
            'error' => $e->getMessage()
        ], 500);
    }
}




// app/Http/Controllers/BillController.php
public function bills(BillRequest $request)
{
    $result = $this->userService->fetchCustomerBills(
        $request->input('phon')
    );

    return response()->json([
        'Responce' => [
            'error' => $result['success'] ? 0 : 1,
            'Erorr_Description' => $result['error'] ?? '',
            'data' => $result['data'] ?? []
        ]
    ], $result['success'] ? 200 : ($result['code'] ?? 400));
}



// public function usageLogs(Request $request)
// {
//     // توقع أن التاريخ مثل "5/2025"
//     $startInput = $request->input('StartTime'); // مثال: 5/2025
//     $endInput = $request->input('EndTime');     // مثال: 5/2025

//     try {
//         // حلل التاريخ بصيغة m/Y
//         $start = Carbon::createFromFormat('m/Y', $startInput)->startOfMonth()->format('Ymd000000');
//         $end = Carbon::createFromFormat('m/Y', $endInput)->endOfMonth()->format('Ymd235959');
//     } catch (\Exception $e) {
//         return response()->json([
//             'message' => 'تنسيق التاريخ غير صالح. استخدم mm/YYYY',
//             'succeeded' => false,
//             'data' => [],
//         ], 422);
//     }

//     $result = $this->userService->fetchUsageLogs(
//         $request->input('userName'),
//         $request->input('userPswd'),
//         $start,
//         $end
//     );

//     return response()->json([
//         'message' => $result['succeeded'] ? 'Success' : 'Failed',
//         'succeeded' => $result['succeeded'],
//         'data' => $result['data'],
//     ], $result['succeeded'] ? 200 : 400);
// }





// public function usageLogs(Request $request)
// {
//     $startInput = $request->input('StartTime'); // مثال: 5/2025
//     $endInput = $request->input('EndTime');     // مثال: 5/2025

//     try {
//         // حلل التاريخ بصيغة m/Y
//         $start = Carbon::createFromFormat('m/Y', $startInput)->startOfMonth()->format('Ymd000000');
//         $end = Carbon::createFromFormat('m/Y', $endInput)->endOfMonth()->format('Ymd235959');
//     } catch (\Exception $e) {
//         return response()->json([
//             'message' => 'تنسيق التاريخ غير صالح. استخدم mm/YYYY',
//             'succeeded' => false,
//             'data' => [],
//         ], 422);
//     }

//     $result = $this->userService->fetchUsageLogs(
//         $request->input('userName'),
//         $request->input('userPswd'),
//         $start,
//         $end
//     );

//     if ($result['succeeded']) {
//         $logs = collect($result['data']);

//         $totalUpload = $logs->sum('upload_MB');
//         $totalDownload = $logs->sum('download_MB');
//         $total = $logs->sum('total_MB');

//         return response()->json([
//             'message' => 'Success',
//             'succeeded' => true,
//             'data' => $logs,
//             'summary' => [
//                 'total_upload_GB' => round($totalUpload / 1024, 2),
//                 'total_download_GB' => round($totalDownload / 1024, 2),
//                 'total_GB' => round($total / 1024, 2),
//             ]
//         ]);
//     }

//     return response()->json([
//         'message' => 'Failed',
//         'succeeded' => false,
//         'data' => [],
//     ], 400);
// }




public function show(Request $request)
{
    $request->validate([
        'userName' => 'required|string',
        'userPswd' => 'required|string',
        'StartTime' => 'required|string|regex:/^\d{14}$/', // YYYYMMDDHHMMSS
        'EndTime' => 'required|string|regex:/^\d{14}$/',
    ]);

    try {
        $data = $this->userService->getSubscriberInfo(
            $request->input('userName'),
            $request->input('userPswd'),
            $request->input('StartTime'),
            $request->input('EndTime')
        );

        return response()->json([
            'message' => 'Success',
            'succeeded' => true,
            'data' => $data
        ]);

    } catch (\Throwable $e) {
        Log::error('API Error', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'message' => $e->getMessage(),
            'succeeded' => false
        ], 500);
    }
}
/////////////////////////////////////////////////////




public function packageInfo(Request $request)
{
    $request->validate([
        'userName' => 'required|string',
        'userPswd' => 'required|string',
    ]);

    try {
        $data = $this->userService->getSubscriberPackageInfo(
            $request->input('userName'),
            $request->input('userPswd')
        );

        return response()->json([
            'message' => 'Success',
            'succeeded' => true,
            'data' => $data
        ]);
    } catch (\Throwable $e) {
        Log::error('API Error (Package Info)', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'message' => $e->getMessage(),
            'succeeded' => false
        ], 500);
    }
}

/////////////////////////////////////////////////////

        // public function changePassword(ChangePasswordRequest $request)
        // {
        //     return $this->userService->changePassword($request->validated());
        // }
        public function changePassword(Request $request)
        {
            $request->validate([
                'user_name' => 'required|string',
                'oldPassword' => 'required|string',
                'newPassword' => 'required|string|min:6',
                'confirmPassword' => 'required|same:newPassword',
            ]);

            $subscriber = User::where('user_name', $request->user_name)->first();

            if (!$subscriber) {
                return response()->json([
                    'message' => 'المستخدم غير موجود',
                    'succeeded' => false
                ], 404);
            }

            if ($subscriber->password !== $request->oldPassword) {
                return response()->json([
                    'message' => 'كلمة المرور القديمة غير صحيحة',
                    'succeeded' => false
                ], 401);
            }

            $subscriber->password = $request->newPassword;
            $subscriber->save();

            return response()->json([
                'message' => 'تم تغيير كلمة المرور بنجاح',
                'succeeded' => true
            ]);
        }

////////////////////////////////////////////////////


        public function logout(Request $request)
{
    try {
        // 1. حذف جميع Tokens المستخدم
        $deletedTokens = $request->user()->tokens()->delete();

        // 2. التحقق من أن الحذف تم بنجاح
        if ($deletedTokens === 0) {
            throw new \Exception('No tokens found to delete');
        }

        // 3. إرجاع رسالة نجاح
        return response()->json([
            'success' => true,
            'message' => 'Logged out from all devices',
            'tokens_deleted' => $deletedTokens // عدد الـ Tokens المحذوفة
        ]);

    } catch (\Exception $e) {
        // 4. تسجيل الخطأ في السجلات
        Log::error('Logout failed: ' . $e->getMessage(), [
            'user_id' => $request->user()->id,
            'time' => now()
        ]);

        // 5. إرجاع رسالة خطأ
        return response()->json([
            'success' => false,
            'message' => 'Logout failed. Please try again.',
            'error' => $e->getMessage() // (اختياري) إظهار تفاصيل الخطأ في وضع التطوير
        ], 500);
    }
}



////////////////////////////////////////////////////////////////////////////////////////////////////////////


public function impersonateTemporary(Request $request)
{
    $response = $this->userService->impersonateAsTemporaryUser(
        $request->user()->id // ID المشرف الحالي
    );

    return response()->json($response);
}


public function cleanTemporaryUsers()
    {
        $response = $this->userService->cleanTemporaryUsers();

        return response()->json($response);
    }

    public function suspendUser(int $userId)
    {
        $response = $this->userService->suspendUser($userId);
        return response()->json($response);
    }

    public function reactivateUser(int $userId)
    {
        $response = $this->userService->reactivateUser($userId);
        return response()->json($response);
    }

    public function forceLogoutUser(int $userId)
    {
        $response = $this->userService->forceLogoutUser($userId);
        return response()->json($response);
    }

    }
