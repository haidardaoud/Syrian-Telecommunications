<?php
namespace App\Services;

use App\Repositories\ContentRepository;
use App\Repositories\SectionRepository;
use Illuminate\Support\Facades\Storage;

class ContentService {
    protected $contentRepo;

    public function __construct(ContentRepository $contentRepo) {
        $this->contentRepo = $contentRepo;
    }


    public function createContent(array $data) {
        return $this->contentRepo->create($data);
    }

    public function updateContent($id, array $data) {
        return $this->contentRepo->update($id, $data);
    }

    public function deleteContent($id) {
        $content = $this->contentRepo->find($id);
        if ($content) {
            $content->mediaContents()->each(function ($mediaContent) {
                Storage::delete($mediaContent->path);
                $mediaContent->delete();
            });
            return $this->contentRepo->delete($id);
        }
        return false;
    }


//     public function getAllContents() {
//     $contents = $this->contentRepo->all();
//     return $this->cleanResponse($contents);
// }
public function getAllContents() {
    $contents = $this->contentRepo->all();
    return $this->cleanResponse($contents);
}

public function getContent($id) {
    $content = $this->contentRepo->find($id);
    return $this->cleanResponse($content);
}

// دالة لتصفية الحقول الفارغة
private function cleanResponse($data) {
    if ($data instanceof \Illuminate\Support\Collection || is_array($data)) {
        return collect($data)->map(function ($item) {
            return collect($item)->filter(function ($value) {
                return $value !== null && $value !== '';
            });
        });
    }

    // إذا كان عنصرًا مفردًا
    return collect($data)->filter(function ($value) {
        return $value !== null && $value !== '';
    });
}

}
