<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Property;
use App\Models\PropertyGroup;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        $title = "لیست محصولات";
        return view('admin.product.list',compact('title'));
    }
    public function edit($id){
        $title = "لیست محصولات";
        $product = Product::query()->find($id);
        $categories = Category::query()->pluck('title','id');
        $colors = Color::query()->pluck('title','id');
        $brands = Brand::query()->pluck('title','id');
        return view('admin.product.edit',compact('title','product','brands','categories','colors'));
    }
    public function create(){
        $title = "اضافه کردن محصولات";
        $categories = Category::query()->pluck('title','id');
        $brands = Brand::query()->pluck('title','id');
        $colors = Color::query()->pluck('title','id');

        return view('admin.product.create',compact('title','categories','brands','colors'));
    }
    public function store(Request $request){
        $image = Product::saveImage($request->file);
       $product = Product::query()->create([
            'title'=> $request->input('title'),
            'title_en' => $request->input('title_en'),
            'slug' => Helper::make_slug($request->input('title')),
            'price' => $request->input('price'),
            'count' => $request->input('count'),
            'image' => $image,
            'guaranty'=> $request->input('guaranty'),
            'discount'=> $request->input('discount'),
            'description'=> $request->input('description'),
            'is_special'=> $request->input('is_special') === 'on',
            'special_expiration'=> $request->input('is_expiration') !==null ? Verta::parse($request->input('special_expiration'))->datetime() : now(),
            'category_id'=> $request->input('category_id'),
            'brand_id'=> $request->input('brand_id')
        ]);
       $colors = $request->input('colors');
       $product->colors()->attach($colors);
        return redirect()->route('products.index')->with('message','محصول با موفقیت اضافه شد');
    }
    public function update(Request $request,$id){
        $product = Product::query()->find($id);
        $image = Product::saveImage($request->file);
        $product->update([
            'title'=> $request->input('title'),
            'title_en' => $request->input('title_en'),
            'slug' => Helper::make_slug($request->input('title')),
            'price' => $request->input('price'),
            'count' => $request->input('count'),
            'image' => ($request->file ? $image : $product->image),
            'guaranty'=> $request->input('guaranty'),
            'discount'=> $request->input('discount'),
            'description'=> $request->input('description'),
            'is_special'=> $request->input('is_special') === 'on',
            'special_expiration'=> $request->input('is_expiration') !==null ? Verta::parse($request->input('special_expiration'))->datetime() : now(),
            'category_id'=> $request->input('category_id'),
            'brand_id'=> $request->input('brand_id')
        ]);
        $colors = $request->input('colors');
        $product->colors()->sync($colors);
        return redirect()->route('products.index')->with('message','محصول با موفقیت ویرایش شد');
    }
    public function addProperties($id){
        $product = Product::query()->find($id);
        $property_groups =  PropertyGroup::query()->get();
        return view('admin.product.create_property',compact('property_groups','product'));
    }
    public function storeProperties(Request $request,$id){
        $product = Product::query()->find($id);
        $product->properties()->sync($request->properties);
        return redirect()->route('products.index')->with('message','ویژگیها با موفقیت اضافه شد');

    }
}
