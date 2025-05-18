<?php
namespace App\Http\Controllers;

use App\Http\Requests\MediaPageRequest;
use App\Services\MediaPageService;
use Illuminate\Http\Request;

class MediaPageController extends Controller {
    protected $mediaPageService;

    public function __construct(MediaPageService $mediaPageService) {
        $this->mediaPageService = $mediaPageService;
    }

    // إضافة وسائط لصفحة
// إضافة وسائط لصفحة
public function store(MediaPageRequest $request, $pageId) {
    $files = $request->file('media');
    $type = $request->input('type');
    $media = $this->mediaPageService->addMediaToPage($pageId, $files, $type);
    return response()->json($media);
}

// تعديل وسائط
public function update(MediaPageRequest $request, $id) {
    $pageId = $request->input('page_id');
    $files = $request->file('media'); // يمكن أن تكون مصفوفة من الملفات
    $type = $request->input('type');

    $media = $this->mediaPageService->updateMedia($id, $pageId, $files, $type);
    return response()->json($media);
}

public function index($pageId) {
    $media = $this->mediaPageService->getMediaByPage($pageId);
    return response()->json($media);
}

}
