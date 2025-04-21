<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'location',
        'start_date',
        'group_id',
        'serial_id'
    ];

    protected $casts = [
        'start_date' => 'datetime',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
