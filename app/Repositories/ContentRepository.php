<?php
namespace App\Repositories;

use App\Models\Content;
use App\Models\Section;

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
// ContentRepository.php

//   /**
//      * جلب المحتوى حسب اسم الـ section مع دعم pagination
//      *
//      * @param string $sectionTitle
//      * @param int $perPage
//      * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
//      */
//     public function getBySectionTitle($section_title, $perPage = 10)
//     {
//         return Content::with(['mediaContents', 'section'])
//             ->whereHas('section', function($query) use ($section_title) {
//                 $query->where('section_title', 'LIKE', $section_title);
//             })
//             ->paginate($perPage);
//     }


public function getBySectionTitle($section_title, $perPage = 10)
{
    return Content::with(['mediaContents', 'section' => function($query) {
            $query->select('id', 'section_title', 'page_id');
        }])
        ->whereHas('section', function($query) use ($section_title) {
            $query->where('section_title', $section_title);
        })
        ->select('contents.*') // استثناء section_id من النتيجة
        ->paginate($perPage);
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
