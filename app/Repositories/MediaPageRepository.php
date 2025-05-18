<?php
namespace App\Repositories;

use App\Models\MediaPage;

class MediaPageRepository {
    public function all() {
        return MediaPage::all();
    }

    public function find($id) {
        return MediaPage::find($id);
    }

    public function create(array $data) {
        return MediaPage::create($data);
    }

    public function createMultiple(array $mediaData) {
        return MediaPage::insert($mediaData);
    }

    public function update($id, array $data) {
        $media = $this->find($id);
        if ($media) {
            $media->update($data);
        }
        return $media;
    }

    public function delete($id) {
        return MediaPage::destroy($id);
    }
    // MediaPageRepository.php
public function getByPage($pageId) {
    return MediaPage::where('page_id', $pageId)->get();
}

}
