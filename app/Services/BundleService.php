<?php
namespace App\Services;


use App\Repositories\BundleRepository;
class BundleService
{
    protected BundleRepository $bundleRepository;

    public function __construct(BundleRepository $bundleRepository)
    {
        $this->bundleRepository = $bundleRepository;
    }

    public function getUserBundles(int $userId)
{
    $subscriptions = $this->bundleRepository->getUserSubscriptionsWithServices($userId);

    $result = [];

    foreach ($subscriptions as $subscription) {
        $baseService = $subscription->service;

        $base = [
            'name' => $baseService->name,
            'price' => $baseService->price,
            'type' => $baseService->type, // مثل: speed_upgrade, addon_bundle, misc
        ];

        $extraBundles = $this->bundleRepository->getExtraBundles($subscription->id)->map(function ($bill) {
            return [
                'price' => $bill->price,
                'status' => $bill->status,
                'date' => $bill->bill_date,
            ];
        });

        $result[] = [
            'subscription_id' => $subscription->id,
            'base_bundle' => $base,
            'extra_bundles' => $extraBundles
        ];
    }

    return $result;
}

}
