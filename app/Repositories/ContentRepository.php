<?php
namespace App\Repositories;

use App\Models\Content;

class ContentRepository {
    // public function all() {
    //     return Content::all();
    // }
    public function all() {
        return Content::with('mediaContents')->get();
    }
    public function find($id) {
        return Content::with('mediaContents')->find($id);
    }

    public function create(array $data) {
        return Content::create($data);
    }

    public function update($id, array $data) {
        $content = $this->find($id);
        if ($content) {
            $content->update($data);
        }
        return $content;
    }

    public function delete($id) {
        $content = $this->find($id);
        if ($content) {
            $content->mediaContents()->delete();
            $content->delete();
        }
    }
}
