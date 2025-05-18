<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreEmployeeRequest;
use App\Models\Log;
use App\Services\User\UserService;

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

/////////////////////////////////////////////////////

        public function changePassword(ChangePasswordRequest $request)
        {
            return $this->userService->changePassword($request->validated());
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
