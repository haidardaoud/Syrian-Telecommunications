<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    public $table='offers';
    use HasFactory;
    protected $fillable=[
        'service_id',
        'desciption',
        'valid_until',
        'title'
    ];
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
