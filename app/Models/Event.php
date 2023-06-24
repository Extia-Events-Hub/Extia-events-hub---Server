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

    //Relationship to User
    public function users() {
        return $this->belongsTo(User::class);
    }
}
