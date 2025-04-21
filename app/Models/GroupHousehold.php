<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupHousehold extends Model
{
    use HasFactory;
    protected $table = 'group_household';
    protected  $fillable = [
        'household_id',
        'group_id',
        'status'
    ];

    public function household(){
        return $this->belongsTo(Household::class, 'household_id');
    }

    public function group(){
        return $this->belongsTo(Group::class, 'group_id');
    }
}
