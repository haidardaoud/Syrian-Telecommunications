<?php

namespace App\Services;

use App\Config\GlobalSettings;
use App\DTO\UsageLogDTO;
use Carbon\Carbon;
//use App\DTO\UsageLogDTO;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;


class CompanyAuthService
{

////////////////////////////////////////////////////////////////////////////////////////////////////////

// public function login(array $credentials): array
// {
//     try {
//         $url = $this->buildLoginUrl($credentials);
//         Log::debug("Company API Request URL: " . $url);

//         $response = Http::timeout(GlobalSettings::getTimeout())
//             ->retry(GlobalSettings::getRetryAttempts(), GlobalSettings::getRetryDelay())
//             ->get($url);

//         if ($response->successful()) {
//             return [
//                 'success' => true,
//                 'data' => $response->json()
//             ];
//         }

//         return [
//             'success' => false,
//             'error' => $response->body(),
//             'code' => $response->status()
//         ];

//     } catch (\Exception $e) {
//         Log::error("Company API Error: " . $e->getMessage());
//         return [
//             'success' => false,
//             'error' => 'Service unavailable',
//             'code' => 503
//         ];
//     }
// }

private function buildLoginUrl(array $credentials): string
{
    $baseUrl = "http://syriantelecom.com.sy/nSync/selfPortal.php";

    $queryParams = [
        'F_ID' => 1,
        'userName' => $credentials['user_name'],
        'userPswd' => $credentials['password'],
        'LangCo' => 1,
        'isWeb' => 1
    ];

    return $baseUrl . '?' . http_build_query($queryParams);
}
////////////////////////////////////////////////////////////////////////////////ظظظظظظظظظظظظظظظظظ////
public function login(array $credentials): array
{
    try {
        $url = $this->buildLoginUrl($credentials);
        Log::debug("Company API Request URL: " . $url);

        $response = Http::timeout(GlobalSettings::getTimeout())
            ->retry(GlobalSettings::getRetryAttempts(), GlobalSettings::getRetryDelay())
            ->get($url);

        // تحقق من أن الرد يحتوي على بيانات صحيحة
        if (!$response->successful()) {
            return [
                'success' => false,
                'error' => 'اسم المستخدم أو كلمة المرور غير صحيحة',
                'code' => 401
            ];
        }

        $responseData = $response->json();

        // تحقق من وجود حقل يشير إلى نجاح المصادقة
        if (isset($responseData['success']) && !$responseData['success']) {
            return [
                'success' => false,
                'error' => $responseData['message'] ?? 'اسم المستخدم أو كلمة المرور غير صحيحة',
                'code' => 401
            ];
        }

        return [
            'success' => true,
            'data' => $responseData
        ];

    } catch (\Exception $e) {
        Log::error("Company API Error: " . $e->getMessage());
        return [
            'success' => false,
            'error' => 'الخدمة غير متوفرة حالياً',
            'code' => 503
        ];
    }
}
//ظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظ//

// app/Services/CompanyAuthService.php
public function getBundles(string $username, string $password): array
{
    try {
        $url = $this->buildBundlesUrl($username, $password);
        Log::debug("Bundles API URL: " . $url);

        $response = Http::timeout(GlobalSettings::getTimeout())
            ->retry(GlobalSettings::getRetryAttempts(), GlobalSettings::getRetryDelay())
            ->get($url);

        if (!$response->successful()) {
            throw new \Exception('Failed to fetch bundles');
        }

        $data = $response->json();

        if (!isset($data['Basic']))
        {
            throw new \Exception('Invalid bundles response');
        }

        return $data;

    } catch (\Exception $e) {
        Log::error("Bundles API Error: " . $e->getMessage());
        return [
            'Basic' => 0,
            'bundles' => []
        ];
    }
}

private function buildBundlesUrl(string $username, string $password): string
{
    $baseUrl = "http://syriantelecom.com.sy/nSync/APIS.php";

    $queryParams = [
        'API_ID' => 1,
        'F_ID' => 6, // حسب ما ذكرت F_ID=6
        'vSource' => 1,
        'isWeb' => 1,
        'LangCo' => 1,
        'userName' => $username,
        'userPswd' => $password
    ];

    return $baseUrl . '?' . http_build_query($queryParams);
}



// app/Services/CompanyAuthService.php
//use Illuminate\Support\Facades\Http;

public function getBills(string $phoneNumber): array
{
    try {
        $url = "http://syriantelecom.com.sy/nSync/Android_t.php?" . http_build_query([
            'phon' => $phoneNumber,
            'notDayn' => 0,
            'allNumbers' => 1
        ]);

        $response = Http::timeout(30)
            ->withoutVerifying()
            ->get($url);

        $body = $response->body();

        // تعديل خاص لحل مشكلة علامات الاقتباس في data
        $pattern = '/"data":"(\[.*\])"/U';

        if (preg_match($pattern, $body, $matches)) {
            $jsonData = str_replace('\\"', '"', $matches[1]);
            $jsonData = stripslashes($jsonData);
            $body = preg_replace($pattern, '"data":' . $jsonData, $body);
        }

        if (!$response->successful()) {
            throw new \Exception('فشل في الاتصال بالخادم: ' . $response->status());
        }

        $firstDecode = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('تنسيق JSON غير صالح في الرد الأول بعد التعديل: ' . json_last_error_msg());
        }

        if (!isset($firstDecode['Responce'])) {
            throw new \Exception('الرد لا يحتوي على مفتاح Responce');
        }

        // الآن حقل data أصبح مصفوفة ولا حاجة لفك تشفير إضافي
        return $firstDecode['Responce'];

    } catch (\Exception $e) {
        return [
            'error' => 1,
            'Erorr_Description' => $e->getMessage(),
            'data' => []
        ];
    }
}
////////////////////////////////////////////////////////////////////////////////////

public function fetchSubscriberPackages(string $username, string $password): array
{
    $url = "http://syriantelecom.com.sy/nSync/selfPortal.php?" . http_build_query([
        'API_ID' => 1,
        'F_ID' => 3,
        'vSource' => 1,
        'isWeb' => 1,
        'LangCo' => 1,
        'userName' => $username . '@tarassul.sy',
        'userPswd' => $password,
    ]);

    $response = Http::timeout(30)->get($url);

    if (!$response->successful()) {
        throw new \Exception("API request failed with status: " . $response->status());
    }

    $data = json_decode($response->body(), true);

    if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
        throw new \Exception("Invalid API response format");
    }

    $packages = array_map(function ($package) {
        // تحويل التواريخ من صيغة 20220420111826 إلى datetime قابل للعرض
        $formatDate = function($str) {
            if (!$str || strlen($str) !== 14) return null;
            $dt = \DateTime::createFromFormat('YmdHis', $str);
            return $dt ? $dt->format('Y/m/d h:i:s A') : null;
        };

        // تحويل الحجم من كيلوبايت إلى جيجابايت مع تقريب
        $maxServiceGB = $package['MaxServiceUsage'] > 0 ? round($package['MaxServiceUsage'] / 1024, 2) : 'غير محدد';

        // تحويل المتبقي من الحجم من بايت إلى ميجابايت مع تقريب
        $freeVolumeMB = $package['FreeVolume'] > 0 ? round($package['FreeVolume'] / (1024 * 1024), 2) : 'غير محدد';

        // حساب النسبة المئوية المتبقية
        $percentage = 'غير محدد';
        if (is_numeric($package['MaxServiceUsage']) && $package['MaxServiceUsage'] > 0 && is_numeric($package['FreeVolume']) && $package['FreeVolume'] >= 0) {
            $percentageValue = ($package['FreeVolume'] / ($package['MaxServiceUsage'] * 1024)) * 100;
            $percentage = round($percentageValue, 1) . '%';
        }

        return [
            'الباقة' => isset($package['ProductName']) ? html_entity_decode($package['ProductName'], ENT_QUOTES, 'UTF-8') : null,
            'السرعة' => $package['Speed'] ?? null,
            'تاريخ الاشتراك' => $formatDate($package['EffTime']),
            'انتهاء الصلاحية' => $formatDate($package['ExpTime']),
            'حجم الباقة' => is_numeric($maxServiceGB) ? $maxServiceGB . ' GB' : $maxServiceGB,
            'المتبقي من الباقة' => is_numeric($freeVolumeMB) ? $freeVolumeMB . ' MB' : $freeVolumeMB,
            'النسبة المتبقية' => $percentage,
        ];
    }, $data);

    return [
        'subscriber' => $username,
        'packages' => $packages,
    ];
}


/////////////////////////////////////////////////////////////////////////////////
//this is working 99%
// public function fetchSubscriberPackages(string $username, string $password): array
    // {
    //     $url = "http://syriantelecom.com.sy/nSync/selfPortal.php?" . http_build_query([
    //         'API_ID' => 1,
    //         'F_ID' => 3,
    //         'vSource' => 1,
    //         'isWeb' => 1,
    //         'LangCo' => 1,
    //         'userName' => $username . '@tarassul.sy',
    //         'userPswd' => $password,
    //     ]);

    //     $response = Http::timeout(30)->get($url);

    //     if (!$response->successful()) {
    //         throw new \Exception("API request failed with status: " . $response->status());
    //     }

    //     $data = json_decode($response->body(), true);

    //     if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
    //         throw new \Exception("Invalid API response format");
    //     }

    //     // هنا نعالج البيانات ونختار الحقول المهمة من الرد (مثلاً نرجع كل الباقات كما هي)

    //     $packages = array_map(function ($package) {
    //         return [
    //             'ProductID'       => $package['ProductID'] ?? null,
    //             'ProductName'     => isset($package['ProductName']) ? html_entity_decode($package['ProductName'], ENT_QUOTES, 'UTF-8') : null,
    //             'RatingMode'      => $package['RatingMode'] ?? null,
    //             'MaxServiceUsage' => $package['MaxServiceUsage'] ?? null,
    //             'FreeTime'        => $package['FreeTime'] ?? null,
    //             'FreeVolume'      => $package['FreeVolume'] ?? null,
    //             'FreeAuthTimes'   => $package['FreeAuthTimes'] ?? null,
    //             'EffTime'         => $package['EffTime'] ?? null,
    //             'ExpTime'         => $package['ExpTime'] ?? null,
    //             'LocRestrictType' => $package['LocRestrictType'] ?? null,
    //             'OnlineSessionNum'=> $package['OnlineSessionNum'] ?? null,
    //             'Speed'           => $package['Speed'] ?? null,
    //             'AccumulateInfo'  => $package['AccumulateInfo'] ?? [],
    //         ];
    //     }, $data);

    //     return [
    //         'subscriber' => $username,
    //         'packages' => $packages,
    //     ];
    // }




/////////////////////////////////////////////////////////////////////////////////////

// this is working 99%
// public function fetchSubscriberData(
//     string $username,
//     string $password,
//     string $startTime,
//     string $endTime
// ): array {
//     $url = "http://syriantelecom.com.sy/nSync/selfPortal.php?" . http_build_query([
//         'F_ID' => 4,
//         'userName' => $username . '@tarassul.sy',
//         'userPswd' => $password,
//         'StartTime' => $startTime,
//         'EndTime' => $endTime,
//         'LangCo' => 1,
//         'isWeb' => 1
//     ]);

//     $response = Http::timeout(30)->get($url);

//     if (!$response->successful()) {
//         throw new \Exception("API request failed with status: " . $response->status());
//     }

//     $data = json_decode($response->body(), true);

//     if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
//         throw new \Exception("Invalid API response format");
//     }

//     return [
//         'subscriber' => $username,
//         'sessions' => $data
//     ];
// }
public function fetchSubscriberData(
    string $username,
    string $password,
    string $startTime,
    string $endTime
): array {
    $url = "http://syriantelecom.com.sy/nSync/selfPortal.php?" . http_build_query([
        'F_ID' => 4,
        'userName' => $username . '@tarassul.sy',
        'userPswd' => $password,
        'StartTime' => $startTime,
        'EndTime' => $endTime,
        'LangCo' => 1,
        'isWeb' => 1
    ]);

    $response = Http::timeout(30)->get($url);

    if (!$response->successful()) {
        throw new \Exception("API request failed with status: " . $response->status());
    }

    $data = json_decode($response->body(), true);

    if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
        throw new \Exception("Invalid API response format");
    }

    // معالجة البيانات لإرجاع الحقول المطلوبة فقط
    $sessions = array_map(function ($session) {
        $beginTime = $session['BeginTime'] ?? null;
        $endTime = $session['EndTime'] ?? null;

        $durationSeconds = $session['TimeLength'] ?? 0;
        $hours = floor($durationSeconds / 3600);
        $minutes = floor(($durationSeconds % 3600) / 60);
        $duration = sprintf('%02d:%02d', $hours, $minutes);

        $uploadMb = isset($session['InputBytes']) ? round($session['InputBytes'] / (1024 * 1024), 2) : 0;
        $downloadMb = isset($session['OutputBytes']) ? round($session['OutputBytes'] / (1024 * 1024), 2) : 0;
        $totalMb = round($uploadMb + $downloadMb, 2);

        return [
            'start_time'   => $beginTime,
            'end_time'     => $endTime,
            'duration'     => $duration,
            'upload_mb'    => $uploadMb,
            'download_mb'  => $downloadMb,
            'total_mb'     => $totalMb,
        ];
    }, $data);

    return [
        'subscriber' => $username,
        'sessions' => $sessions
    ];
}
//
///////////////self care/////////////////////////////////
// public function loginToSelfCare(string $username, string $password): ?array
// {
//     $url = 'http://syriantelecom.com.sy/nSync/selfcare/login.php';

//     $response = Http::asJson()->post($url, [
//         'username_SCID' => $username,
//         'password_SCID' => $password,
//     ]);

//     if ($response->successful() && $response->json('T')) {
//         return $response->json();
//     }

//     return null;
// }
public function loginToSelfCare(string $username, string $password): ?array
{
    $url = 'http://syriantelecom.com.sy/nSync/selfcare/login.php';

    $response = Http::asJson()->post($url, [
        'username_SCID' => $username,
        'password_SCID' => $password,
    ]);
    Log::info('API Response: ' . $response->body());

    if ($response->successful()) {
        $json = $response->json();
        if (isset($json['T']) && !empty($json['T'])) {
            return $json;
        }
    }

    return null;
}

public function getServices(string $contractId, string $authToken): ?array
{
    $url = 'http://syriantelecom.com.sy/nSync/selfcare/services.php';

    $response = Http::asJson()->post($url, [
        'contractId' => $contractId,
        'authToken' => $authToken,
    ]);

    if ($response->successful()) {
        return $response->json();
    }

    return null;
}

// app/Services/Company/CompanyAuthService.php

public function getBills_slefcare(string $customerId, string $authToken): ?array
{
    $url = 'http://syriantelecom.com.sy/nSync/selfcare/bills.php';

    $response = Http::asJson()->post($url, [
        'customerID' => $customerId,
        'authToken' => $authToken,
    ]);

    if ($response->successful()) {
        return $response->json()['documents']['item'] ?? [];
    }

    return null;
}

///////////////////////////////////////////////////////////////////////////////////////

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






