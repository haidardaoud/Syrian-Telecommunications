<?php
namespace App\Http\Controllers;

use App\Http\Requests\ContentRequest;
use App\Services\ContentService;
use Illuminate\Http\Request;

class ContentController extends Controller {
    protected $contentService;

    public function __construct(ContentService $contentService) {
        $this->contentService = $contentService;
    }

    // --- Admin ---
    public function index() {
        $contents = $this->contentService->getAllContents();
        return response()->json($contents);
    }

    public function store(ContentRequest $request) {
        $content = $this->contentService->createContent($request->all());
        return response()->json($content);
    }

    public function show($id) {
        $content = $this->contentService->getContent($id);
        return response()->json($content);
    }

    public function update(ContentRequest $request, $id) {
        $content = $this->contentService->updateContent($id, $request->all());
        return response()->json($content);
    }

    public function destroy($id) {
        $this->contentService->deleteContent($id);
        return response()->json(['message' => 'تم الحذف بنجاح']);
    }


    //////////////////////////////////////////////////////////////////////////
 // ContentController.php

 public function showBySectionName(Request $request, $section_title)
 {
     $perPage = $request->input('per_page', 10);
     $response = $this->contentService->getContentBySectionName($section_title, $perPage);

     return response()->json($response, $response['succeeded'] ? 200 : 404);
 }

    /////////////////////////////////////////////////////////////////

    // --- User (عرض المحتوى) ---
    // public function userIndex() {
    //     $contents = $this->contentService->getAllContents();
    //     return response()->json($contents->load('mediaContents'));
    // }
    public function userIndex() {
        $contents = $this->contentService->getAllContents();
        return response()->json($contents);
    }
    // public function userShow($id) {
    //     $content = $this->contentService->getContent($id);
    //     return response()->json($content->load('mediaContents'));
    // }
    public function userShow($id) {
        $contents = $this->contentService->getContent($id);
        return response()->json($contents);
    }
}













    //  public function __construct(protected CompanyInformationService $companyService)
    //  {
    //     $this->companyService = $companyService;
    //  }

    //  public function store(InformationRequest $request)
    // {
    //     $info = $this->companyService->createWithMedia($request->validated());
    //     return response()->json($info, 200, [], JSON_UNESCAPED_SLASHES);
    // }

    // public function update(UpdateInformationRequest $request, $id)
    // {
    //     $info = $this->companyService->updateWithMedia($id, $request->validated());
    //     return response()->json($info);
    // }


    // public function destroy($id)
    // {
    //     $this->companyService->delete($id);
    //     return response()->json(['message' => 'Deleted successfully']);
    // }

    // public function show($id)
    // {
    //     $info = $this->companyService->get($id);
    //     return response()->json($info);
    // }

    // public function getByTitle($title)
    // {
    //     $info = $this->companyService->getByTitle($title);

    //     if (!$info) {
    //         return response()->json(['message' => 'Not found'], 404);
    //     }

    //     return response()->json($info);
    // }

