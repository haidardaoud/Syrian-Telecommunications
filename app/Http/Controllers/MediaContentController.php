<?php
namespace App\Http\Controllers;

use App\Http\Requests\MediaContentRequest;
use App\Services\MediaContentService;
use Illuminate\Http\Request;

class MediaContentController extends Controller {
    protected $mediaContentService;

    public function __construct(MediaContentService $mediaContentService) {
        $this->mediaContentService = $mediaContentService;
    }

    // إضافة وسائط لمحتوى
    public function store(MediaContentRequest $request, $contentId) {

        $pageId = $request->input('page_id');
        $sectionId = $request->input('section_id');
        $type = $request->input('type');
        $files = $request->file('media');

        $media = $this->mediaContentService->addMediaToContent($contentId, $pageId, $sectionId, $files, $type);
        return response()->json($media);
    }

    // تعديل وسائط
    public function update(MediaContentRequest $request, $id) {
        $file = $request->file('media');
        $type = $request->input('type');
        $media = $this->mediaContentService->updateMedia($id, $file, $type);
        return response()->json($media);
    }

    // حذف وسائط
    public function destroy($id) {
        $this->mediaContentService->deleteMedia($id);
        return response()->json(['message' => 'تم الحذف بنجاح']);
    }

      public function show(Request $request, $contentId) {
        $pageId = $request->input('page_id');
        $sectionId = $request->input('section_id');

        // استدعاء الدالة في الخدمة للتحقق من البيانات وجلب الوسائط
        return $this->mediaContentService->getMediaByContentId($contentId, $pageId, $sectionId);
    }
}
