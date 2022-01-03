<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::orderBy('created_at', 'DESC')->with('custom_fields', 'parent', 'childrens', 'products', 'packs')->get();

        return response()->json($categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'slug' => 'required|unique:categories',
            'icon' => 'required',
            'status' => 'required',
            'show_in_home' => 'required',
            'position' => 'required',
            'link' => 'required'
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->icon = $request->icon;
        if ($request->image) {
            $this->validate($request,[
                'image' => 'required|mimes:png,jpg',
            ]);
            $imageName = Carbon::now()->timestamp . '.' . $request->image->extension();
            $request->image->storeAs('public/category/images', $imageName);
            $category->image = $imageName;
        }
        $category->status = $request->status;
        $category->show_in_home = $request->show_in_home;
        $category->position = $request->position;
        if ($request->ad_image) {
            $this->validate($request,[
                'ad_image' => 'required|mimes:png,jpg',
            ]);
            $ad_imageName = Carbon::now()->timestamp . '.' . $request->ad_image->extension();
            $request->ad_image->storeAs('public/category/ad_images', $ad_imageName);
            $category->ad_image = $ad_imageName;
        }
        $category->link = $request->link;
        $category->save();

        $category->custom_fields()->sync($request->custom_fields);

        return response()->json($category);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'.$id,
            'icon' => 'required',
            'status' => 'required',
            'show_in_home' => 'required',
            'position' => 'required',
            'link' => 'required'
        ]);

        $category = Category::find($id);
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->icon = $request->icon;
        if ($request->newimage) {
            $this->validate($request, [
                'newimage' => 'required|mimes:png,jpg'
            ]);
            if ($category->image) {
                unlink(storage_path('app/public/category/images/' . $category->image));
            }
            $imageName = Carbon::now()->timestamp . '.' . $request->newimage->extension();
            $request->newimage->storeAs('public/category/images', $imageName);
            $category->image = $imageName;
        }
        else{
            $category->image = $category->image;
        }
        $category->status = $request->status;
        $category->show_in_home = $request->show_in_home;
        $category->position = $request->position;
        if ($request->newad_image) {
            $this->validate($request, [
                'newad_image' => 'required|mimes:png,jpg'
            ]);
            if ($category->ad_image) {
                unlink(storage_path('app/public/category/ad_images/' . $category->ad_image));
            }
            $ad_imageName = Carbon::now()->timestamp . '.' . $request->newad_image->extension();
            $request->newad_image->storeAs('public/category/ad_images', $ad_imageName);
            $category->ad_image = $ad_imageName;
        }
        else{
            $category->ad_image = $category->ad_image;
        }
        $category->link = $request->link;
        $category->save();

        $category->custom_fields()->sync($request->custom_fields);

        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        if ($category->image) {
            unlink(storage_path('app/public/category/images/' . $category->image));
        }
        if ($category->ad_image) {
            unlink(storage_path('app/public/category/ad_images/' . $category->ad_image));
        }
        $category->destroy($id);

        return response()->json($category);
    }
}
