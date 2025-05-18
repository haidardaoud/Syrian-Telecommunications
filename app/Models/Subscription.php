<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    public $taable='subscriptions';
    use HasFactory;
    protected $fillable=[
        'user_id',
        'service_id',
        'description'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    public function bill()
    {
        return $this->hasMany(Bill::class,'subscription_id');
    }
}
