<?php
namespace App\Services;

use App\Models\Content;
use App\Models\Section;
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


//////////////////////////////////////////////////////////////////////////////////////////
// ContentService.php

//  /**
//      * جلب المحتوى حسب اسم الـ section مع دعم pagination
//      *
//      * @param string $sectionTitle
//      * @param int $perPage
//      * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
//      */
//     public function getContentBySectionTitle(string $section_title, int $perPage = 10): array
//     {
//         $contents = $this->contentRepo->getBySectionTitle($section_title, $perPage);

//         return [
//             'message' => $contents->isEmpty()
//                 ? 'No content found for section: ' . $section_title
//                 : 'Content retrieved successfully',
//             'succeeded' => !$contents->isEmpty(),
//             'data' => $contents->items(),
//             'pagination' => [
//                 'total' => $contents->total(),
//                 'per_page' => $contents->perPage(),
//                 'current_page' => $contents->currentPage(),
//                 'last_page' => $contents->lastPage()
//             ]
//         ];
//     }
public function getContentBySectionName($name, $perPage = 10)
    {
        $contents = $this->contentRepo->getBySectionTitle($name, $perPage);

        if ($contents->isEmpty()) {
            return [
                'message' => 'لا يوجد محتوى للقسم: ' . $name,
                'succeeded' => false,
                'data' => null,
                'pagination' => null
            ];
        }

        // نعدل البيانات هنا فقط لإزالة التكرار
        $filteredData = $contents->map(function($content) {
            return [
                'id' => $content->id,
                'paragraph_title' => $content->paragraph_title,
                'description' => $content->description,
                'location' => $content->location,
                'special' => $content->special,
                'date' => $content->date,
                'phone_number' => $content->phone_number,
                'email' => $content->email,
                'work_time' => $content->work_time,
                'media_contents' => $content->mediaContents,
                'section' => $content->section // نترك العلاقة كما هي لكن بدون section_id المكرر
            ];
        });

        return [
            'message' => 'تم جلب محتوى القسم بنجاح',
            'succeeded' => true,
            'data' => $filteredData,
            'pagination' => [
                'total' => $contents->total(),
                'per_page' => $contents->perPage(),
                'current_page' => $contents->currentPage(),
                'last_page' => $contents->lastPage()
            ]
        ];
    }

/////////ظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظظ/////
//////////////////////////////////////////////////////////////////////////

}
