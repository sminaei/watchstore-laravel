<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Http\Requests\SliderRequest;
use App\Models\Brand;
use App\Models\Slider;
use Illuminate\Http\Request;

class BrandController extends Controller
{
   public function index(){
    $title = "لیست برندها";
    return view('admin.brand.list',compact('title'));
}
public function create(){
    $title = "ایجاد برند";
    return view('admin.brand.create',compact('title'));

}
    public function store(BrandRequest $request){
    $image = Brand::saveImage($request->file);
    Brand::query()->create([
        'title' => $request->input('title'),
        'image' => $image
    ]);
    return redirect()->route('brands.index')->with('message',' جدید با موفقیت اضافه شد برند');
}
    public function edit($id){
    $brand =Brand::query()->find($id);
    $title = "ویرایش برند ";
    return view('admin.brand.edit',compact('title','brand'));

}
    public function update(BrandRequest $request,$id){
    $image = Brand::saveImage($request->file);
    Brand::query()->find($id)->update([
        'title' => $request->input('title'),
        'image' => $image
    ]);

    return redirect()->route('brands.index')->with('message','برند  با موفقیت ویرایش شد');

}
    public function destroy(string $id)
    {
        //
    }
}
