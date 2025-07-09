<?php
namespace App\Services;

use App\Models\Bill;
use App\Models\Subscription;
use App\Models\Service;
use App\Repositories\UserRepository;
use App\Repositories\BillRepository;
use App\Services\CompanyAuthService;
use Illuminate\Support\Carbon;

class BillService
{
    protected $repo;
    protected $companyAuthService;
         protected UserRepository $userRepository;

    public function __construct(BillRepository $repo, CompanyAuthService $companyAuthService,UserRepository $userRepository)
    {
        $this->repo = $repo;
        $this->companyAuthService = $companyAuthService;
        $this->userRepository = $userRepository;
    }

    public function purchaseMultipleBundles(array $credentials, int $subscriptionId, $bundles)
{
    // التحقق من المستخدم من خلال API الشركة
    $loginResult = $this->companyAuthService->login($credentials);

    if (!$loginResult['success']) {
        return response()->json([
            'success' => false,
            'message' => 'اسم المستخدم أو كلمة المرور غير صحيحة',
            'data' => []
        ], 401);
    }

    // التحقق من أن الاشتراك يخص هذا المستخدم
    $user = $this->userRepository->findByUserName($credentials['user_name']);
    $subscription = Subscription::find($subscriptionId);

    if (!$subscription || $subscription->user_id !== $user->id) {
        return response()->json([
            'success' => false,
            'message' => 'الاشتراك غير تابع لهذا المستخدم',
            'data' => []
        ], 403);
    }

    // إذا كانت باقة واحدة فقط (كائن وليس مصفوفة)
    if (!is_array($bundles) || array_keys($bundles) === array_keys(array_filter($bundles, 'is_string'))) {
        $bundles = [$bundles]; // تحويلها لمصفوفة فيها عنصر واحد
    }

    $createdBills = [];

    foreach ($bundles as $bundle) {
        if (!isset($bundle['name'], $bundle['price'])) {
            continue; // تخطي الباقة إذا ناقصة بيانات
        }

        // ✅ التحقق من وجود هذه الباقة (name + price) في جدول الخدمات
        $serviceExists = Service::where('name', $bundle['name'])
            ->where('price', $bundle['price'])
            ->exists();

        if (!$serviceExists) {
            return response()->json([
                'success' => false,
                'message' => 'الباقة "' . $bundle['name'] . '" غير موجودة أو السعر غير صحيح',
                'data' => []
            ], 422);
        }

        // إنشاء الفاتورة
        $bill = Bill::create([
            'subscription_id' => $subscriptionId,
            'price' => $bundle['price'],
            'bill_date' => now(),
            'status' => 'unpaid'
        ]);

        $createdBills[] = [
            'bundle_name' => $bundle['name'],
            'price' => $bundle['price'],
            'bill_id' => $bill->id
        ];
    }

    return response()->json([
        'success' => true,
        'message' => 'تمت إضافة الباقات بنجاح',
        'data' => $createdBills
    ]);
}

}
// //////////////////////////////////////////////////////////////////////////////
//     protected CompanyAuthService $companyAuthService;
//     protected UserRepository $userRepository;

//     public function __construct(CompanyAuthService $companyAuthService, UserRepository $userRepository)
//     {
//         $this->companyAuthService = $companyAuthService;
//         $this->userRepository = $userRepository;
//     }

//     public function purchaseBundle(array $data, string $username, string $password): array
//     {
//         // تحقق من صحة اسم المستخدم وكلمة المرور
//         $loginResponse = $this->companyAuthService->login([
//             'user_name' => $username,
//             'password' => $password
//         ]);

//         if (!$loginResponse['success'] || empty($loginResponse['data'])) {
//             return [
//                 'success' => false,
//                 'message' => 'اسم المستخدم أو كلمة المرور غير صحيحة',
//                 'data' => []
//             ];
//         }

//         // جلب بيانات المستخدم المسجل في النظام
//         $user = $this->userRepository->findByUserName($username);
//         if (!$user) {
//             return [
//                 'success' => false,
//                 'message' => 'المستخدم غير موجود في النظام',
//                 'data' => []
//             ];
//         }

//         // التحقق من أن الاشتراك يعود لهذا المستخدم
//         $subscription = Subscription::where('id', $data['subscription_id'])
//             ->where('user_id', $user->id)
//             ->first();

//         if (!$subscription) {
//             return [
//                 'success' => false,
//                 'message' => 'الاشتراك غير موجود أو لا يعود للمستخدم',
//                 'data' => []
//             ];
//         }

//         // جلب الخدمة المرتبطة بالاشتراك
//         $service = $subscription->service;

//         // إنشاء فاتورة جديدة للباقة الإضافية
//         $bill = Bill::create([
//             'subscription_id' => $subscription->id,
//             'price' => $data['price'],
//             'bill_date' => Carbon::now(),
//             'status' => 'unpaid'
//         ]);

//         return [
//             'success' => true,
//             'message' => 'تم شراء الباقة الإضافية بنجاح',
//             'data' => [
//                 'service_name' => $service->name,
//                 'bundle_name' => $data['name'],
//                 'price' => $data['price'],
//                 'bill_id' => $bill->id
//             ]
//         ];
//     }
// }
