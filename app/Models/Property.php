<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'property_group_id',
    ];
    public function property_group(){
        return $this->belongsTo(PropertyGroup::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class,'product_property');
    }
}
