<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $table='notifications';
    use HasFactory;
    protected $fillable = [
        'user_id',
        'message',
        'date_sent',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
