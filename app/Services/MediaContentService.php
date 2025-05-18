<?php
namespace App\Services;

use App\Repositories\ContentRepository;
use App\Repositories\MediaContentRepository;
use App\Repositories\PageRepository;
use App\Repositories\SectionRepository;
use Illuminate\Support\Facades\Storage;

class MediaContentService {
    protected $mediaContentRepo;
    protected $pageRepo;
    protected $sectionRepo;
    protected $contentRepo;

    public function __construct(
        MediaContentRepository $mediaContentRepo,
        PageRepository $pageRepo,
        SectionRepository $sectionRepo,
        ContentRepository $contentRepo
    ) {
        $this->mediaContentRepo = $mediaContentRepo;
        $this->pageRepo = $pageRepo;
        $this->sectionRepo = $sectionRepo;
        $this->contentRepo = $contentRepo;
    }
   
    public function addMediaToContent($contentId, $pageId, $sectionId, $files, $type) {
        // التحقق من وجود الصفحة
        $page = $this->pageRepo->find($pageId);
        if (!$page) {
            return response()->json(['error' => 'الصفحة غير موجودة'], 404);
        }

        // التحقق من وجود القسم وارتباطه بالصفحة
        $section = $this->sectionRepo->find($sectionId);
        if (!$section || $section->page_id != $pageId) {
            return response()->json(['error' => 'القسم غير موجود أو غير مرتبط بالصفحة المحددة'], 404);
        }

        // التحقق من وجود المحتوى وارتباطه بالقسم
        $content = $this->contentRepo->find($contentId);
        if (!$content || $content->section_id != $sectionId) {
            return response()->json(['error' => 'المحتوى غير موجود أو غير مرتبط بالقسم المحدد'], 404);
        }

        // تخزين الوسائط
        $mediaData = [];
        foreach ($files as $file) {
            $path = $file->store('public/uploads/contents');
            $fileUrl = asset('storage/uploads/contents/' . basename($path));

            $mediaData[] = [
                'content_id' => $contentId,
                'type' => $type,
                'file' => $fileUrl,
            ];
        }

        $this->mediaContentRepo->createMultiple($mediaData);

        return $mediaData;
    }


    public function deleteMedia($id) {
        $media = $this->mediaContentRepo->find($id);
        if ($media) {
            Storage::delete(str_replace(asset('storage'), 'public', $media->file));
            return $media->delete();
        }
        return false;
    }

    public function updateMedia($id, $file, $type) {
        $media = $this->mediaContentRepo->find($id);
        if ($media) {
            // حذف الملف القديم
            Storage::delete(str_replace(asset('storage'), 'public', $media->file));

            // رفع الملف الجديد
            $path = $file->store('public/uploads/contents');
            $fileUrl = asset('storage/uploads/contents/' . basename($path));

            return $this->mediaContentRepo->update($id, [
                'type' => $type,
                'file' => $fileUrl,
            ]);
        }
        return null;
    }

     public function getMediaByContentId($contentId, $pageId, $sectionId) {
        // التحقق من وجود الصفحة
        $page = $this->pageRepo->find($pageId);
        if (!$page) {
            return response()->json(['error' => 'الصفحة غير موجودة'], 404);
        }

        // التحقق من وجود القسم وارتباطه بالصفحة
        $section = $this->sectionRepo->find($sectionId);
        if (!$section || $section->page_id != $pageId) {
            return response()->json(['error' => 'القسم غير موجود أو غير مرتبط بالصفحة المحددة'], 404);
        }

        // التحقق من وجود المحتوى وارتباطه بالقسم
        $content = $this->contentRepo->find($contentId);
        if (!$content || $content->section_id != $sectionId) {
            return response()->json(['error' => 'المحتوى غير موجود أو غير مرتبط بالقسم المحدد'], 404);
        }

        // جلب الوسائط المرتبطة بـ content_id
        $media = $this->mediaContentRepo->findByContentId($contentId);

        if ($media->isEmpty()) {
            return response()->json(['message' => 'لا توجد وسائط للمحتوى المحدد'], 404);
        }

        return response()->json($media);
    }

}
