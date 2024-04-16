<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const ACCESS_LEVEL_ADMIN = 1;
    const ACCESS_LEVEL_TECHNICIAN = 2;

    const VERIFIED_USER = '1';
    const UNVERIFIED_USER = '0';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'access_level_id'
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
        'password' => 'hashed',
    ];

    public function setNameAttribute($name){
        $this->attributes['name'] =  Str::lower($name);
    }

    public function setEmailAttribute($email){
        $this->attributes['email'] =  Str::lower($email);
    }

    //Accessor
    public function getNameAttribute($name){
        return ucwords($name);
    }



    public function isVerified(){
        return $this->verified == User::VERIFIED_USER;
    }

    public static function generateVerificationCode(){
        return Str::random(40);
    }
}
