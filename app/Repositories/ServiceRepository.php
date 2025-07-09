<?php
namespace App\Repositories;

use App\Models\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ServiceRepository
{
    public function all(int $perPage = 10): LengthAwarePaginator
    {
        return Service::paginate($perPage);
    }

    public function create(array $data): Service
    {
        return Service::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $service = Service::find($id);
        return $service ? $service->update($data) : false;
    }

    public function delete(int $id): bool
    {
        return (bool) Service::destroy($id);
    }

    public function find(int $id)
    {
        return Service::find($id);
    }
    public function getAllBundles()
    {
        return Service::where('name', 'LIKE', '% شحن باقة%')->get();
    }

    public function findBundleById($id)
    {
        return Service::find($id);
    }
}
