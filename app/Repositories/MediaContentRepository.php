<?php
namespace App\Repositories;

use App\Models\MediaContent;

class MediaContentRepository {
    public function all() {
        return MediaContent::all();
    }

    public function find($id) {
        return MediaContent::find($id);
    }

    public function create(array $data) {
        return MediaContent::create($data);
    }

    public function createMultiple(array $mediaData) {
        return MediaContent::insert($mediaData);
    }

    public function update($id, array $data) {
        $media = $this->find($id);
        if ($media) {
            $media->update($data);
        }
        return $media;
    }

    public function delete($id) {
        return MediaContent::destroy($id);
    }
        public function findByContentId($contentId) {
        return MediaContent::where('content_id', $contentId)->get();
    }
}
