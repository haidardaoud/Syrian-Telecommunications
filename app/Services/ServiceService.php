<?php
namespace App\Services;

use App\Repositories\ServiceRepository;
use Illuminate\Support\Facades\Log;

class ServiceService
{
    protected ServiceRepository $serviceRepo;

    public function __construct(ServiceRepository $serviceRepo)
    {
        $this->serviceRepo = $serviceRepo;
    }

    public function getAllServices(int $perPage = 10): array
    {
        try {
            $services = $this->serviceRepo->all($perPage);

            return [
                'success' => true,
                'data' => $services,
                'message' => 'تم جلب الخدمات بنجاح'
            ];
        } catch (\Exception $e) {
            Log::error('Failed to get services: ' . $e->getMessage());
            return $this->handleError($e);
        }
    }

    public function createService(array $data): array
    {
        try {
            $service = $this->serviceRepo->create($data);

            return [
                'success' => true,
                'data' => $service,
                'message' => 'تم إنشاء الخدمة بنجاح'
            ];
        } catch (\Exception $e) {
            Log::error('Failed to create service: ' . $e->getMessage());
            return $this->handleError($e);
        }
    }

    public function updateService(int $id, array $data): array
    {
        try {
            $updated = $this->serviceRepo->update($id, $data);

            return [
                'success' => $updated,
                'message' => $updated ? 'تم التحديث بنجاح' : 'الخدمة غير موجودة'
            ];
        } catch (\Exception $e) {
            Log::error('Failed to update service: ' . $e->getMessage());
            return $this->handleError($e);
        }
    }

    public function deleteService(int $id): array
    {
        try {
            $deleted = $this->serviceRepo->delete($id);

            return [
                'success' => $deleted,
                'message' => $deleted ? 'تم الحذف بنجاح' : 'الخدمة غير موجودة'
            ];
        } catch (\Exception $e) {
            Log::error('Failed to delete service: ' . $e->getMessage());
            return $this->handleError($e);
        }
    }

    public function getServiceById(int $id): array
    {
        try {
            $service = $this->serviceRepo->find($id);

            return [
                'success' => (bool) $service,
                'data' => $service,
                'message' => $service ? 'تم جلب الخدمة' : 'الخدمة غير موجودة'
            ];
        } catch (\Exception $e) {
            Log::error('Failed to get service: ' . $e->getMessage());
            return $this->handleError($e);
        }
    }

    protected function handleError(\Exception $e): array
    {
        return [
            'success' => false,
            'message' => 'حدث خطأ غير متوقع',
            'error' => config('app.debug') ? $e->getMessage() : null
        ];
    }
}
