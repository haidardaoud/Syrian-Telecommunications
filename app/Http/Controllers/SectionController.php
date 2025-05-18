<?php
namespace App\Http\Controllers;

use App\Http\Requests\SectionRequest;
use App\Services\SectionService;
use App\Traits\CleanResponseTrait;
use Illuminate\Http\Request;

class SectionController extends Controller {
    protected $sectionService;
    

    public function __construct(SectionService $sectionService) {
        $this->sectionService = $sectionService;
    }

    // --- Admin ---
    public function index() {
        $sections = $this->sectionService->getAllSections();
        return response()->json($sections);
    }

    public function store(SectionRequest $request) {
        $section = $this->sectionService->createSection($request->all());
        return response()->json($section);
    }

    public function show($id) {
        $section = $this->sectionService->getSection($id);
        return response()->json($section);
    }

    public function update(SectionRequest $request, $id) {
        $section = $this->sectionService->updateSection($id, $request->all());
        return response()->json($section);
    }

    public function destroy($id) {
        $this->sectionService->deleteSection($id);
        return response()->json(['message' => 'تم الحذف بنجاح']);
    }

    // --- User (عرض الأقسام) ---
    public function userIndex() {
        $sections = $this->sectionService->getAllSections();
        return response()->json($sections->load('contents.mediaContents'));
    }

    public function userShow($id) {
        $section = $this->sectionService->getSection($id);
        return response()->json($section->load('contents.mediaContents'));
    }
}
