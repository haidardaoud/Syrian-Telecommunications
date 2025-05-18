<?php
namespace App\Services;

use App\Repositories\PageRepository;

use Illuminate\Support\Facades\Storage;

class PageService {
    protected $pageRepo;

    public function __construct(PageRepository $pageRepo) {
        $this->pageRepo = $pageRepo;
    }

    public function getAllPages() {
        return $this->pageRepo->all();
    }

    public function getPage($id) {
        return $this->pageRepo->find($id);
    }

    public function createPage($data) {
        return $this->pageRepo->create($data);
    }

    public function updatePage($id, $data) {
        return $this->pageRepo->update($id, $data);
    }

    public function deletePage($id) {
        $page = $this->pageRepo->find($id);
        if (!$page) return false;

        $page->mediaPages()->each(function ($media) {
            Storage::delete($media->path);
            $media->delete();
        });

        $page->sections()->each(function ($section) {
            $section->contents()->each(function ($content) {
                $content->mediaContents()->each(function ($mediaContent) {
                    Storage::delete($mediaContent->path);
                    $mediaContent->delete();
                });
                $content->delete();
            });
            $section->delete();
        });

        return $this->pageRepo->delete($id);
    }
}
