<?php
// namespace App\Services;

// use App\Services\Information\InformationService;
// use App\Models\Information;
// use App\Services\Media\MediaService;
// use Illuminate\Support\Facades\Storage;

// class CompanyInformationService
// {
//     public function __construct(
//         protected InformationService $informationService,
//         protected MediaService $mediaService
//     ) {}

//     public function createWithMedia(array $data): Information
//     {
//         $info = $this->informationService->create([
//             'title'       => $data['title'],
//             'type'        => $data['type'],
//             'description' => $data['description'],
//         ]);
    
//         // التأكد من إرسال وسائط الصور
//         if (!empty($data['media']) && is_array($data['media'])) {
//             $paths = [];
//             foreach ($data['media'] as $file) {
//                 $imageName = time() . '_' . $file->getClientOriginalName();
//                 $path = $file->storeAs('public/uploads/information', $imageName);
//                 $paths[] = Storage::url($path);
//             }
//             // تمرير الروابط إلى خدمة الوسائط لتخزينها في قاعدة البيانات
//             $this->mediaService->attachMedia($info, $paths, $data['media_type']);
//         }
//         $info->load('media');
//         return $info;
//     }

//     public function updateWithMedia(int $id, array $data): Information
// {
//     // استخراج البيانات الموجودة فقط دون فرض وجودها
//     $updateData = array_filter([
//         'title' => $data['title'] ?? null,
//         'type' => $data['type'] ?? null,
//         'description' => $data['description'] ?? null,
//     ], fn($v) => !is_null($v)); // حذف القيم null فقط

//     $info = $this->informationService->update($id, $updateData);

//     // التحقق من وجود صور جديدة
//     if (!empty($data['media'])) {
//         // حذف الصور القديمة فقط إن وُجدت
//         $this->mediaService->deleteByInformationId($id);
//         $this->mediaService->attachMedia($info, $data['media'], $data['media_type'] ?? 'image');
//     }

//     return $info;
// }


//     public function delete(int $id): bool
//     {
//         $this->mediaService->deleteByInformationId($id);
//         return $this->informationService->delete($id);
//     }

//     public function get(int $id): ?Information
//     {
//         return $this->informationService->get($id);
//     }

//     public function getByTitle(string $title): ?Information
//     {
//         return $this->informationService->getByTitle($title);
//     }
// }
