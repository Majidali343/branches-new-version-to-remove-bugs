<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HouseholdUser extends Model
{
    use HasFactory;
    protected $table = 'household_user';
    protected $fillable = [
        'user_id',
        'household_id',
        'is_admin',
        'status'
    ];

    public function household(){
        return $this->belongsTo(Household::class, 'household_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
