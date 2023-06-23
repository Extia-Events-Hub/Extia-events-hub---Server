<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'description',
        'image',
        'user_id',
    ];

    //Relationship to User
    public function users (){
        return $this->belongsTo(User::class);
    }
    
    //Relationship to Scannedproduct
    public function products() {
        return $this->hasMany(Scannedproduct::class);
    }
}
