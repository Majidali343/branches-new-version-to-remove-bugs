<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HouseholdHousehold extends Model
{
    use HasFactory;

    protected $table = 'household_household';
    protected $fillable = [
        'requested_household_id',
        'household_id',
        'status'
    ];

    public function household(){
        return $this->belongsTo(Household::class, 'household_id');
    }

    public function requestedHousehold(){
        return $this->belongsTo(Household::class, 'requested_household_id');
    }
}
