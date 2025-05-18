<?php

namespace App\Models;

use App\Traits\HideNullAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaContent extends Model
{
    use HasFactory;
    use HideNullAttributes;

    protected $table = 'media_contents';

    protected $fillable = ['content_id', 'type', 'file'];

    protected $casts = [
        'file' => 'string'
    ];

    // علاقة مع المحتوى
    public function content()
    {
        return $this->belongsTo(Content::class);
    }
}
