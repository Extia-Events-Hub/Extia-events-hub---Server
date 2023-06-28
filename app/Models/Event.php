<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;    

    protected $fillable = [
        'title',
        'shortDescription',  
        'longDescription',
        'mode',
        'startDate',
        'endDate',
        'startTime',
        'endTime',
        'image',       
        'user_id',
        'max_participants',
    ];

    protected $casts = [
        'title' => 'array',
        'shortDescription' => 'array',
        'longDescription' => 'array',
        'mode' => 'array',        
        'image' => 'array',
        'user_id' => 'integer',
        'max_participants'  => 'integer',
    ];

    //Relationship to User
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
