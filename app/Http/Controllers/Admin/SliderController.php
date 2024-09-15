<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SliderRequest;
use App\Models\Slider;

class SliderController extends Controller
{
    public function index(){
        $title = "لیست اسلایدرها";
        return view('admin.slider.list',compact('title'));
    }
    public function create(){
        $title = "ایجاد اسلایدر";
        return view('admin.slider.create',compact('title'));

    }
    public function store(SliderRequest $request){
        $image = Slider::saveImage($request->file);
        Slider::query()->create([
            'title' => $request->input('title'),
            'url' => $request->input('url'),
            'image' => $image
        ]);
        return redirect()->route('sliders.index')->with('message','اسلایدر جدید با موفقیت اضافه شد');
    }
    public function edit($id){
        $slider =Slider::query()->find($id);
        $title = "ویرایش اسلایدر";
        return view('admin.slider.edit',compact('title','slider'));

    }
    public function update(SliderRequest $request,$id){
        $image = Slider::saveImage($request->file);
         Slider::query()->find($id)->update([
             'title' => $request->input('title'),
             'url' => $request->input('url'),
             'image' => $image
         ]);

        return redirect()->route('sliders.index')->with('message',' اسلایدر با موفقیت ویرایش شد');

    }
}
