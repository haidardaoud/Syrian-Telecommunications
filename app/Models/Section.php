<?php

namespace App\Models;

use App\Traits\HideNullAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;
    use HideNullAttributes;

    protected $table = 'sections';

    protected $fillable = ['page_id', 'section_title'];

    // علاقة مع الصفحة
    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    // علاقة مع المحتويات
    public function contents()
    {
        return $this->hasMany(Content::class, 'section_id');
    }
}
