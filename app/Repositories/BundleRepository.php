<?php
// app/Repositories/BundleRepository.php
namespace App\Repositories;

//use App\DTO\Bill;
use App\DTO\Bundle;
use App\Services\CompanyAuthService;
use App\models\subscription;
use App\Models\Bill;

class BundleRepository
{
    public function __construct(
        private CompanyAuthService $companyAuthService
    ) {}

    public function getBundles(string $username, string $password): array
    {
        $response = $this->companyAuthService->getBundles($username, $password);

        return [
            'basic' => $response['Basic'] ?? 0,
            'bundles' => array_map(
                fn($bundle) => new Bundle(
                    $bundle['name'],
                    $bundle['price'],
                    $bundle['id'],
                    $bundle['isEnable'],
                    $bundle['status'],
                    $bundle['Vol']
                ),
                $response['bundles'] ?? []
            )
        ];
    }
    public function getCustomerBills(string $phoneNumber): array
    {
        $apiResponse = $this->companyAuthService->getBills($phoneNumber);

        if (!isset($apiResponse['error']) || $apiResponse['error'] == 1) {
            return [
                'success' => false,
                'error' => $apiResponse['Erorr_Description'] ?? 'خطأ غير معروف في استدعاء API',
                'code' => 400
            ];
        }

        if (!isset($apiResponse['data']) || !is_array($apiResponse['data'])) {
            return [
                'success' => false,
                'error' => 'البيانات المستلمة من API غير صحيحة',
                'code' => 400
            ];
        }

        return [
            'success' => true,
            'data' => array_map(function($bill) {
                return [
                    'cycle' => $bill['cycle'],
                    'from' => $bill['from'],
                    'to' => $bill['to'],
                    'total' => $bill['total'],
                    'value' => $bill['value'],
                    'mNumber' => $bill['mNumber'],
                    'documentRefNum' => $bill['documentRefNum']
                ];
            }, $apiResponse['data'])
        ];
    }
///////////////////////////////////////////////////////////////////////////////////////////////
public function getUserSubscriptionsWithServices(int $userId)
    {
        return Subscription::with('service')->where('user_id', $userId)->get();
    }

    public function getExtraBundles(int $subscriptionId)
    {
        return Bill::where('subscription_id', $subscriptionId)->get();
    }
}
