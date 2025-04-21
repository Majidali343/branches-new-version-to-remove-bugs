<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Household extends Model
{
    use HasFactory;
    protected $table = 'households';
    protected $fillable = [
        'name',
        'profile_img',
        'household_bio',
        'address',
        'household_id',
        'city',
        'state',
        'country',
        'zip',
        'serial_id',
        'premium_expiry'
    ];

    public function users()
    {
        return $this->belonser::clagsToMany();
    }
    public function householdUser()
    {
        return $this->hasOne(HouseholdUser::class)->where('user_id', auth()->id());
    }
    public function groups() {
        return $this->belongsToMany(Group::class);
    }
}
