<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    public $table='bills';
    use HasFactory;
    protected $fillable=[
        'subscription_id',
        'price',
        'bill_date',
        'status'
    ];
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
