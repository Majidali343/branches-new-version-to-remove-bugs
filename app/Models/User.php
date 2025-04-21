<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    
    protected $fillable = [
        'username',
        'fullname',
        'email',
        'password',
        'profile_img',
        'dob',
        'address',
        'fcm',
        'gender',
        'country_code',
        'phone',
        'non_account_member'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    
    public function userHousehold()
    {
        return $this->hasOne(HouseholdUser::class, 'user_id');
    }
    public function userGroup()
    {
        return $this->hasOne(GroupUser::class, 'user_id');
    }
    public function userTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }
    public function tasks()
    {
        return $this->belongsToMany(Task::class);
    }
    public function households() {
        return $this->belongsToMany(Household::class);
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
