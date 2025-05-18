<?php

namespace App\Models;

use App\Traits\HideNullAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaPage extends Model
{
    use HasFactory;
    use HideNullAttributes;


    protected $table = 'media_pages';

    protected $fillable = ['page_id', 'type', 'file'];

    protected $casts = [
        'file' => 'string'
    ];

    // علاقة مع الصفحة
    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
