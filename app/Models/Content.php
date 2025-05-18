<?php

namespace App\Models;

use App\Traits\HideNullAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;
    use HideNullAttributes;

    protected $table = 'contents';

    protected $fillable = [
        'section_id',
        'paragraph_title',
        'description',
        'location',
        'special',
        'date',
        'phone_number',
        'email',
        'work_time'
    ];

    // علاقة مع الوسائط الخاصة بالمحتوى
    public function mediaContents()
    {
        return $this->hasMany(MediaContent::class, 'content_id');
    }

    // علاقة مع القسم
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
