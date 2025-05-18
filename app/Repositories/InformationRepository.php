<?php
namespace App\Repositories;

use App\Models\Information;

class InformationRepository
{
    public function create(array $data): Information
    {
        return Information::create($data);
    }

    public function find(int $id): ?Information
    {
        return Information::with('media')->find($id);
    }

    public function update(Information $information, array $data): Information
    {
        // فقط حدّث الحقول التي أُرسلت فعلياً
        $information->fill($data)->save();
        return $information;
    }
    

    public function delete(Information $information): bool
    {
        return $information->delete();
    }

    public function findByTitle(string $title): ?Information
    {
        return Information::with('media')->where('title', $title)->first();
    }
}
