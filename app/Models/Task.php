<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'due_date',
        'created_date',
        'description',
        'assigned_to',
        'event_id',
        'status',
        'cost',
        'complete_note',
    ];

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
