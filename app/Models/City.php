<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',        
        'user_id',
    ];


    //Relationship to User
    public function users() {
        return $this->belongsTo(User::class);
    }
}
