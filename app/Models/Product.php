<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'title_en',
        'slug',
        'price',
        'review',
        'count',
        'image',
        'guaranty',
        'discount',
        'description',
        'is_special',
        'special_expiration',
        'status',
        'category_id',
        'brand_id'

    ];
    public function category(){
      return  $this->belongsTo(Category::class);
    }
    public function brand(){
        return $this->belongsTo(Brand::class);
    }
    public function colors(){
        return $this->belongsToMany(Color::class,'color_product');
    }

    public function properties()
    {
        return $this->belongsToMany(Property::class,'product_property');
    }
    public static function saveImage($file)
    {

        if ($file) {
            $name = time().'.'.$file->extension();
            $smallImage = Image::make($file->getRealPath());
            $bigImage = Image::make($file->getRealPath());
            $smallImage->resize(256, 256, function ($constariant) {
                $constariant->aspectRatio();
            });

            Storage::disk('local')->put('admin/products/small/' .$name, (string) $smallImage->encode('png',90));
            Storage::disk('local')->put('admin/products/big/' .$name, (string) $bigImage->encode('png',90));
            return $name;
        } else {
            return '';
        }

    }
    public function comments(){
        return $this->hasMany(Comment::class);
    }
}
