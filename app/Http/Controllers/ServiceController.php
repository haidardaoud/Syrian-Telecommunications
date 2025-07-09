<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceRequest;
use App\Services\ServiceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function __construct(public ServiceService $serviceService) {
       $this->serviceService = $serviceService;
    }

    /**
     * عرض جميع الخدمات مع Pagination
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 10);
        $response = $this->serviceService->getAllServices($perPage);

        return response()->json($response );
    }

    /**
     * إنشاء خدمة جديدة
     */
    public function store(ServiceRequest $request): JsonResponse
    {
        $response = $this->serviceService->createService($request->validated());
        return response()->json($response, $response['success'] ? 201 : 400);
    }

    /**
     * عرض خدمة محددة
     */
    public function show(int $id): JsonResponse
    {
        $response = $this->serviceService->getServiceById($id);
        return response()->json($response, $response['success'] ? 200 : 404);
    }

    /**
     * تحديث الخدمة
     */
    public function update(ServiceRequest $request, int $id): JsonResponse
    {
        $response = $this->serviceService->updateService($id, $request->validated());
        return response()->json($response, $response['success'] ? 200 : 404);
    }

    /**
     * حذف الخدمة
     */
    public function destroy(int $id): JsonResponse
    {
        $response = $this->serviceService->deleteService($id);
        return response()->json($response, $response['success'] ? 200 : 404);
    }
}
