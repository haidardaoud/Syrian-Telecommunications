<?php

namespace App\Models;

use App\Traits\HideNullAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;
    use HideNullAttributes;

    protected $table = 'pages';

    protected $fillable = ['page_title'];

    // علاقة مع الأقسام
    public function sections()
    {
        return $this->hasMany(Section::class, 'page_id');
    }

    // علاقة مع الوسائط الخاصة بالصفحة
    public function mediaPages()
    {
        return $this->hasMany(MediaPage::class, 'page_id');
    }
}
