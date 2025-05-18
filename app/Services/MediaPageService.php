<?php
namespace App\Services;

use App\Repositories\MediaPageRepository;
use App\Repositories\PageRepository;
use Illuminate\Support\Facades\Storage;

class MediaPageService {
    protected $mediaPageRepo;
    protected $pageRepo;

    public function __construct(MediaPageRepository $mediaPageRepo, PageRepository $pageRepo) {
        $this->mediaPageRepo = $mediaPageRepo;
        $this->pageRepo = $pageRepo;
    }

    public function addMediaToPage($pageId, $files, $type) {
        $mediaData = [];

        foreach ($files as $file) {
            $path = $file->store('public/uploads/pages');
            $fileUrl = asset('storage/uploads/pages/' . basename($path));

            $mediaData[] = [
                'page_id' => $pageId,
                'type' => $type,
                'file' => $fileUrl, // حفظ الـ URL في عمود file كما في الجدول
            ];
        }

        $this->mediaPageRepo->createMultiple($mediaData);

        return $mediaData;
    }

    public function deleteMedia($id) {
        $media = $this->mediaPageRepo->find($id);
        if ($media) {
            Storage::delete(str_replace(asset('storage'), 'public', $media->file));
            return $media->delete();
        }
        return false;
    }

     public function updateMedia($id, $pageId, $files, $type) {
    // التحقق من أن الصفحة موجودة
    $page = $this->pageRepo->find($pageId);
    if (!$page) {
        return response()->json(['error' => 'الصفحة غير موجودة'], 404);
    }

    // التحقق من أن الوسائط موجودة
    $media = $this->mediaPageRepo->find($id);
    if (!$media) {
        return response()->json(['error' => 'الوسائط غير موجودة'], 404);
    }

    // حذف الملفات القديمة
    Storage::delete(str_replace(asset('storage'), 'public', $media->file));

    // تخزين الملفات الجديدة
    $mediaData = [];
    foreach ($files as $file) {
        $path = $file->store('public/uploads/pages');
        $fileUrl = asset('storage/uploads/pages/' . basename($path));
        
        $mediaData[] = [
            'type' => $type,
            'file' => $fileUrl,
        ];
    }

    // تحديث الوسائط
    $this->mediaPageRepo->update($id, [
        'type' => $type,
        'file' => implode(',', array_column($mediaData, 'file')), // حفظ المسارات في ملف واحد مفصول بفاصلة
    ]);

    return $mediaData;
}
// MediaPageService.php
public function getMediaByPage($pageId) {
    // التحقق من أن الصفحة موجودة
    $page = $this->pageRepo->find($pageId);
    if (!$page) {
        return response()->json(['error' => 'الصفحة غير موجودة'], 404);
    }

    // جلب الوسائط المتعلقة بالصفحة
    $media = $this->mediaPageRepo->getByPage($pageId);

    return $media;
}

}
