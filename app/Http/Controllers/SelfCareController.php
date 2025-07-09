<?php
namespace App\Http\Controllers;

use App\Services\SelfCareService;
use Illuminate\Http\Request;

class SelfCareController extends Controller
{
    protected $selfCareService;

    public function __construct(SelfCareService $selfCareService)
    {
        $this->selfCareService = $selfCareService;
    }

    public function login(Request $request)
{
    $request->validate([
        'username_SCID' => 'required|string',
        'password_SCID' => 'required|string',
    ]);

    $data = $this->selfCareService->login(
        $request->username_SCID,
        $request->password_SCID
    );

    if (!$data) {
        return response()->json(['message' => 'بيانات غير صحيحة أو الخدمة غير متوفرة حالياً.'], 401);
    }

    return response()->json($data);
}


public function showServices(Request $request)
{
    $contractId = $request->input('contractId');
    $authToken = $request->input('authToken');

    if (!$contractId || !$authToken) {
        return response()->json(['error' => 'contractId و authToken مطلوبين'], 400);
    }

    $services = $this->selfCareService->getServicesWithNames($contractId, $authToken);

    return response()->json($services);
}
// app/Http/Controllers/SelfCareController.php

// public function showBills(Request $request)
// {
//     $customerId = $request->input('customerID');
//     $authToken = $request->input('authToken');

//     if (!$customerId || !$authToken) {
//         return response()->json(['error' => 'customerId و authToken مطلوبين'], 400);
//     }

//     $bills = $this->selfCareService->getFormattedBills($customerId, $authToken);

//     return response()->json($bills);
// }
public function showBills(Request $request)
{
    $customerId = $request->input('customerID');
    $authToken = $request->input('authToken');
    $contractId = $request->input('contractId');

    if (!$customerId || !$authToken || !$contractId) {
        return response()->json(['error' => 'customerID و authToken و contractId مطلوبين'], 400);
    }

    $bills = $this->selfCareService->getFormattedBills($customerId, $authToken, $contractId);

    return response()->json($bills);
}

}
