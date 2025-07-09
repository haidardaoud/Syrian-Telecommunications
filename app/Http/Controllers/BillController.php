<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuyBundleRequest;
use App\Models\Bill;
use App\Services\BillService;
use Illuminate\Http\Request;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected BillService $bundlePurchaseService;

    public function __construct(BillService $bundlePurchaseService)
    {
        $this->bundlePurchaseService = $bundlePurchaseService;
    }

    // public function purchasesssssssssss(Request $request)
    // {
    //     $validated = $request->validate([
    //         'subscription_id' => 'required|integer|exists:subscriptions,id',
    //         'name' => 'required|string',
    //         'price' => 'required|integer|min:1',
    //     ]);

    //     $username = $request->query('userName');
    //     $password = $request->query('userPswd');

    //     if (!$username || !$password) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'اسم المستخدم وكلمة المرور مطلوبان',
    //             'data' => []
    //         ], 422);
    //     }

    //     $result = $this->bundlePurchaseService->purchaseBundle($validated, $username, $password);

    //     return response()->json($result);
    // }
    public function purchase(Request $request)
    {
        $credentials = [
            'user_name' => $request->query('userName'),
            'password' => $request->query('userPswd')
        ];

        $subscriptionId = $request->input('subscription_id');
        $bundles = $request->input('bundles');

        if (!$credentials || !$subscriptionId || !$bundles) {
            return response()->json([
                'success' => false,
                'message' => 'البيانات غير مكتملة',
                'data' => []
            ], 400);
        }

        return $this->bundlePurchaseService->purchaseMultipleBundles($credentials, $subscriptionId, $bundles);
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Bill $bill)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bill $bill)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bill $bill)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bill $bill)
    {
        //
    }
}
