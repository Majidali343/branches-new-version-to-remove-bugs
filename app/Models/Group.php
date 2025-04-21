<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'profile_img',
        'description',
        'serial_id',
        'creator_id'
    ];
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    public function households() {
        return $this->belongsToMany(Household::class);
    }
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
