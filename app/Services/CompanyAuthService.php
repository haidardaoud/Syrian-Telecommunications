<?php

namespace App\Services;

use App\Config\GlobalSettings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;


class CompanyAuthService
{
    public function login(array $credentials): array
    {
        // إنشاء مفتاح الكاش بناءً على بيانات الاعتماد
        $cacheKey = 'company_login_' . md5(json_encode($credentials));

        // محاولة استخدام الكاش
        $data = Cache::remember($cacheKey, 60, function () use ($credentials) {
            return $this->sendLoginRequest($credentials);
        });

        return $data;
    }



private function sendLoginRequest(array $credentials): array
{
    try {
        $response = Http::timeout(GlobalSettings::getTimeout())  // استخدام الزمن الأقصى للانتظار من GlobalSettings
            ->retry(GlobalSettings::getRetryAttempts(), GlobalSettings::getRetryDelay())  // استخدام إعدادات إعادة المحاولة من GlobalSettings
            ->acceptJson()
            ->post('https://api.company.com/login-check', $credentials); // اتصال API الخارجي

        if ($response->successful()) {
            return [
                'success' => true,
                'data' => $response->json(),
            ];
        }

        return [
            'success' => false,
            'error' => $response->json(),
            'code' => $response->status(),
        ];
    } catch (\Exception $e) {
        Log::error('Company API error', [
            'error' => $e->getMessage(),
            'credentials' => $credentials
        ]);

        return [
            'success' => false,
            'error' => ['message' => 'Company API error'],
            'code' => 500,
        ];
    }
}
// public function resetPassword(array $data): array
// {
//     return Cache::remember('reset_cache_key', 60, function () use ($data) {
//         return $this->sendResetRequest($data);
//     });
// }

// private function sendResetRequest(array $data): array
// {
//     try {
//         $response = Http::post('https://api.company.com/reset-password', $data);
//         if ($response->successful()) {
//             return ['success' => true, 'data' => $response->json()];
//         } else {
//             return ['success' => false, 'error' => $response->json(), 'code' => $response->status()];
//         }
//     } catch (\Exception $e) {
//         Log::error('Reset API error', ['error' => $e->getMessage()]);
//         return ['success' => false, 'error' => 'API error', 'code' => 500];
//     }
// }
public function verifyCredentials(array $credentials): array
{
    $cacheKey = 'company_verify_' . md5(json_encode($credentials));

    return Cache::remember($cacheKey, 60, function () use ($credentials) {
        return $this->sendVerifyRequest($credentials);
    });
}

private function sendVerifyRequest(array $credentials): array
{
    try {
        $response = Http::timeout(GlobalSettings::getTimeout())
            ->retry(GlobalSettings::getRetryAttempts(), GlobalSettings::getRetryDelay())
            ->post('https://api.company.com/verify-password', $credentials);

        if ($response->successful()) {
            return ['success' => true];
        }

        return [
            'success' => false,
            'error'   => $response->json(),
            'code'    => $response->status()
        ];
    } catch (\Exception $e) {
        Log::error('Company Verify API error', ['error' => $e->getMessage()]);
        return ['success' => false, 'error' => 'API error', 'code' => 500];
    }
}

}







// class CompanyAuthService
// {
//     public function login(array $credentials): array
//     {
//         // إنشاء مفتاح الكاش بناءً على بيانات الاعتماد
//         $cacheKey = 'company_login_' . md5(json_encode($credentials));

//         // محاولة استخدام الكاش
//         $data = Cache::remember($cacheKey, 60, function () use ($credentials) {
//             return $this->sendLoginRequest($credentials);
//         });

//         return $data;
//     }

//     private function sendLoginRequest(array $credentials): array
//     {
//         try {
//             $response = Http::timeout(5)  // الحد الأقصى للانتظار 5 ثواني
//                 ->retry(3, 100)  // إعادة المحاولة 3 مرات مع تأخير 100 ميلي ثانية بين كل محاولة
//                 ->acceptJson()
//                 ->post('https://api.company.com/login-check', $credentials); // اتصال API الخارجي

//             if ($response->successful()) {
//                 return [
//                     'success' => true,
//                     'data' => $response->json(),
//                 ];
//             }

//             return [
//                 'success' => false,
//                 'error' => $response->json(),
//                 'code' => $response->status(),
//             ];
//         } catch (\Exception $e) {
//             Log::error('Company API error', [
//                 'error' => $e->getMessage(),
//                 'credentials' => $credentials
//             ]);

//             return [
//                 'success' => false,
//                 'error' => ['message' => 'Company API error'],
//                 'code' => 500,
//             ];
//         }
//     }
// }
