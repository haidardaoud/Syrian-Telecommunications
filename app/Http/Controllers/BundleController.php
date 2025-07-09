<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuyBundleRequest;
use App\Models\Bill;
use App\Services\BundleService;
use Illuminate\Http\Request;


class BundleController extends Controller{
    protected BundleService $bundleService;

    public function __construct(BundleService $bundleService)
    {
        $this->bundleService = $bundleService;
    }

    public function getUserBundles(Request $request)
    {
        $userId = $request->query('user_id');

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'رقم المستخدم مفقود',
                'data' => []
            ], 400);
        }

        $bundles = $this->bundleService->getUserBundles($userId);

        return response()->json([
            'success' => true,
            'message' => 'تم جلب الباقات بنجاح',
            'data' => $bundles
        ]);
    }


}
