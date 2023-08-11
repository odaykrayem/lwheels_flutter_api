<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends \TCG\Voyager\Models\User
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'f_name',
        'l_name' ,
        'phone' ,
        'ref_code' ,
        'ref_times' ,
        'points',
        'balance',
        'password',
    ];

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
        'ref_times' => 'integer',
        'points' => 'integer',
        'balance' => 'double'

    ];


    public function participants (){
        return $this->hasMany(Participant::class);
    }


    public function withdrawals (){
        return $this->hasMany(Withdrawal::class);
    }

    // public function rewardRecords (){
    //     return $this->hasMany(RewardRecord::class);
    // }

    public function rewardRegistries (){
        return $this->hasMany(RewardsRegistry::class);
    }

    public function refRecords (){
        return $this->hasMany(RefRecord::class);
    }

}
