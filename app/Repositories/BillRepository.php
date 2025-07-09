<?php
namespace App\Repositories;

use App\Models\Subscription;
use App\Models\Service;
use App\Models\Bill;

class BillRepository
{
    // public function getSubscriptionWithService(int $subscriptionId)
    // {
    //     return Subscription::with('service', 'user')->find($subscriptionId);
    // }

    // public function findServiceByNameAndPrice(string $name, int $price)
    // {
    //     return Service::where('name', $name)
    //                   ->where('price', $price)
    //                   ->first();
    // }

    // public function createBill(int $subscriptionId, int $price): Bill
    // {
    //     return Bill::create([
    //         'subscription_id' => $subscriptionId,
    //         'price' => $price,
    //         'bill_date' => now()->toDateString(),
    //         'status' => 'unpaid'
    //     ]);
    // }
    public function createBill(array $data)
    {
        return Bill::create($data);
    }
}
