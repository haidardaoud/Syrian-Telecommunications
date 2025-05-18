<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'job_id',
        'user_name',
        'password',
        'is_temporary',
        'is_suspended'

    ];


    public function job_offers()
    {
        return $this->belongsTo(Job_Offer::class);
    }

    public function notification()
    {
        return $this->hasMany(Notification::class,'user_id');
    }
    public function complaint()
    {
        return $this->hasMany(Complaint::class,'user_id');
    }
    public function log()
    {
        return $this->hasMany(Log::class,'user_id');
    }
    public function subscription()
    {
        return $this->hasMany(Subscription::class,'user_id');
    }
    public function tokens()
{
    return $this->hasMany(\Laravel\Sanctum\PersonalAccessToken::class);
}


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
