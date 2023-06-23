<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scannedproduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'scanDate',
        'score',        
        'product_id',
        'user_id',
    ];

    //Relationship to User
    public function users (){
        return $this->belongsTo(User::class);
    }

    //Relationship to User
    public function products (){
        return $this->belongsTo(Product::class);
    }
}
