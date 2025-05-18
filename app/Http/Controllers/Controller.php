<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Traits\CleanResponseTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    // use AuthorizesRequests, ValidatesRequests;
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function executeProtected(callable $callback): JsonResponse
    {
        try {
            $result = $callback();
            return response()->json($result);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->handleException($e, 'البيانات المطلوبة غير موجودة', 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->handleException($e, 'أخطاء في التحقق', 422);
        } catch (\Exception $e) {
            return $this->handleException($e, 'حدث خطأ غير متوقع', 500);
        }
    }

    private function handleException(\Exception $e, string $message, int $statusCode): JsonResponse
    {
        if (app()->environment('production')) {
            Log::error($e->getMessage(), ['exception' => $e]);
        }

        return response()->json([
            'success' => false,
            'message' => $message,
            'error' => config('app.debug') ? $e->getMessage() : null
        ], $statusCode);
    }

}
