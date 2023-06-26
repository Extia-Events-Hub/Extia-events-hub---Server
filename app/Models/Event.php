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
    ];

    protected $casts = [
        'title'=> 'json',      
        'shortDescription' => 'json',
        'longDescription' => 'json',
        'mode'=> 'json',
        'startDate'=> 'json',
        'endDate'=> 'json',
        'startTime'=> 'json',
        'endTime'=> 'json',
        'image'=> 'json',      
        'user_id'=> 'json',
    ];

    //Relationship to User
    public function users() {
        return $this->belongsTo(User::class);
    }
}
