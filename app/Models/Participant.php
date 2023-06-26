<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'userName',
        'userEmail',
        'check',        
        'event_id',
    ];

    //Relationship to Event
    public function events() {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
