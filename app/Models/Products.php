<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "type",
        "brands_id",
        "categories_id",
        "price",
        "availableUnit"
    ];
    protected $hidden = [
        "brands"
    ];


    public function brands(){
       return $this->belongsTo(Brands::class , "brand_id");
    }
    public function categories(){
        return $this->belongsTo(Categories::class);
    }
    public function image(){
        return  $this->hasMany(Images::class);
    }
}
