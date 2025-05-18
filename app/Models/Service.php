<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public $table='services';
    use HasFactory;
    protected $fillable=[
        'name',
        'description',
        'price'
    ];
    
    public function subscription()
    {
        return $this->hasMany(Subscription::class,'sevice_id');
    }
    public function offer()
    {
        return $this->hasMany(Offer::class,'service_id');
    }
    
}
