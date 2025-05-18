<?php
namespace App\Services;

use App\Repositories\SectionRepository;

use Illuminate\Support\Facades\Storage;

class SectionService {
    protected $sectionRepo;

    public function __construct(SectionRepository $sectionRepo) {
        $this->sectionRepo = $sectionRepo;
    }

    public function createSection(array $data) {
        return $this->sectionRepo->create($data);
    }

    public function updateSection($id, array $data) {
        return $this->sectionRepo->update($id, $data);
    }

    public function deleteSection($id) {
        $section = $this->sectionRepo->find($id);
        if ($section) {
            $section->contents()->each(function ($content) {
                $content->mediaContents()->each(function ($mediaContent) {
                    Storage::delete($mediaContent->path);
                    $mediaContent->delete();
                });
                $content->delete();
            });
            return $this->sectionRepo->delete($id);
        }
        return false;
    }


public function getAllSections() {
    return $this->sectionRepo->all();
}

public function getSection($id) {
    return $this->sectionRepo->find($id);
}
}