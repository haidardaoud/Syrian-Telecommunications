<?php
namespace App\Repositories;

use App\Models\Page;
use App\Models\Section;
use App\Models\Content;
use App\Models\MediaPage;
use App\Models\MediaContent;

class PageRepository {
    public function all() {
        return Page::all();
    }

    public function find($id) {
        return Page::with(['sections.contents.mediaContents', 'mediaPages'])->findOrFail($id);
    }

    public function create(array $data) {
        return Page::create($data);
    }

    public function update($id, array $data) {
        $page = $this->find($id);
        if ($page) {
            $page->update($data);
        }
        return $page;
    }

    public function delete($id) {
        $page = $this->find($id);
        if ($page) {
            $page->mediaPages()->delete();
            foreach ($page->sections as $section) {
                $section->contents()->each(function ($content) {
                    $content->mediaContents()->delete();
                    $content->delete();
                });
                $section->delete();
            }
            $page->delete();
        }
    }
}
