<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sliders = Slider::orderBy('created_at', 'DESC')->get();

        return response()->json($sliders);
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
            'image' => 'required|mimes:png,jpg',
            'link' => 'required',
            'position' => 'required',
            'status' => 'required'
        ]);

        $slider = new Slider();
        if ($request->title) {
            $this->validate($request, [
                'title' => 'required'
            ]);
            $slider->title = $request->title;
        }
        $imageName = Carbon::now()->timestamp . '.' . $request->image->extension();
        $request->image->storeAs('public/slider/images', $imageName);
        $slider->image = $imageName;
        $slider->link = $request->link;
        $slider->position = $request->position;
        $slider->status = $request->status;
        $slider->save();

        return response()->json($slider);
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
            'link' => 'required',
            'position' => 'required',
            'status' => 'required'
        ]);

        $slider = Slider::find($id);
        if ($request->title) {
            $this->validate($request, [
                'title' => 'required'
            ]);
            $slider->title = $request->title;
        }
        if ($request->newimage) {
            $this->validate($request, [
                'newimage' => 'required|mimes:png,jpg'
            ]);
            if ($slider->image) {
                unlink(storage_path('app/public/slider/images/' . $slider->image));
            }
            $imageName = Carbon::now()->timestamp . '.' . $request->newimage->extension();
            $request->newimage->storeAs('public/slider/images', $imageName);
            $slider->image = $imageName;
        } else {
            $slider->image = $slider->image;
        }
        $slider->link = $request->link;
        $slider->position = $request->position;
        $slider->status = $request->status;
        $slider->save();

        return response()->json($slider);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $slider = Slider::find($id);
        if ($slider->image) {
            unlink(storage_path('app/public/slider/images/' . $slider->image));
        }
        $slider->destroy($id);
        return response()->json($slider);
    }
}
