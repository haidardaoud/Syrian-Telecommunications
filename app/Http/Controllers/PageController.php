<?php

namespace App\Http\Controllers;

use App\Http\Requests\PageRequest;
use App\Services\PageService;
use Illuminate\Http\Request;

class PageController extends Controller {
    protected $pageService;

    public function __construct(PageService $pageService) {
        $this->pageService = $pageService;
    }

    // --- عمليات الإدارة (Admin) ---
    public function index() {
        $pages = $this->pageService->getAllPages();
        return response()->json($pages);
    }

    public function store(PageRequest $request) {
        $page = $this->pageService->createPage($request->all());
        return response()->json($page);
    }

    public function show($id) {
        $page = $this->pageService->getPage($id);
        return response()->json($page);
    }

    public function update(PageRequest $request, $id) {
        $page = $this->pageService->updatePage($id, $request->all());
        return response()->json($page);
    }

    public function destroy($id) {
        $this->pageService->deletePage($id);
        return response()->json(['message' => 'تم الحذف بنجاح']);
    }

    // --- عرض للمستخدم (User) ---
    public function userIndex() {
        $pages = $this->pageService->getAllPages();
        // جلب الأقسام والمحتويات والوسائط معاً
        return response()->json($pages->load('sections.contents.mediaContents', 'mediaPages'));
    }

    public function userShow($id) {
        $page = $this->pageService->getPage($id);
        return response()->json($page->load('sections.contents.mediaContents', 'mediaPages'));
    }
}
