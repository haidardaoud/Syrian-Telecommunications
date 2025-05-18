<?php
namespace App\Repositories;

use App\Models\Section;

class SectionRepository {
    public function all() {
        return Section::all();
    }

    public function find($id) {
        return Section::with('contents.mediaContents')->find($id);
    }

    public function create(array $data) {
        return Section::create($data);
    }

    public function update($id, array $data) {
        $section = $this->find($id);
        if ($section) {
            $section->update($data);
        }
        return $section;
    }

    public function delete($id) {
        $section = $this->find($id);
        if ($section) {
            $section->contents()->each(function ($content) {
                $content->mediaContents()->delete();
                $content->delete();
            });
            $section->delete();
        }
    }
}
