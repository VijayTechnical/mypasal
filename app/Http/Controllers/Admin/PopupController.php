<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Popup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PopupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $popups = Popup::orderBy('created_at','DESC')->get();

        return response()->json($popups);
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
        $this->validate($request,[
            'title' => 'required',
            'expire_at' => 'required',
            'pages' => 'required',
            'status' => 'required'
        ]);

        $popup = new Popup();
        $popup->title = $request->title;
        if($request->description)
        {
            $this->validate($request,[
                'description' => 'required',
            ]);
            $popup->description = $request->description;
        }
        if($request->image)
        {
            $this->validate($request,[
                'image' => 'required|mimes:png,jpg',
            ]);
            $imageName = Carbon::now()->timestamp . '.' . $request->image->extension();
            $request->image->storeAs('public/popup/images', $imageName);
            $popup->image = $imageName;
        }
        $popup->expire_at = $request->expire_at;
        if($request->link)
        {
            $this->validate($request,[
                'link' => 'required',
            ]);
            $popup->link = $request->link;
        }
        if($request->hide_time)
        {
            $this->validate($request,[
                'hide_time' => 'required',
            ]);
            $popup->hide_time = $request->hide_time;
        }
        $popup->pages = $request->pages;
        $popup->status = $request->status;
        $popup->save();

        return response()->json($popup);
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
        $this->validate($request,[
            'title' => 'required',
            'expire_at' => 'required',
            'pages' => 'required',
            'status' => 'required'
        ]);

        $popup = Popup::find($id);
        $popup->title = $request->title;
        if($request->description)
        {
            $this->validate($request,[
                'description' => 'required',
            ]);
            $popup->description = $request->description;
        }
        if($request->newimage)
        {
            $this->validate($request,[
                'newimage' => 'required|mimes:png,jpg',
            ]);
            if($popup->image)
            {
                unlink(storage_path('app/public/popup/images/'.$popup->image));
            }
            $imageName = Carbon::now()->timestamp . '.' . $request->newimage->extension();
            $request->newimage->storeAs('public/popup/images', $imageName);
            $popup->image = $imageName;
        }
        else{
            $popup->image = $popup->image;
        }
        $popup->expire_at = $request->expire_at;
        if($request->link)
        {
            $this->validate($request,[
                'link' => 'required',
            ]);
            $popup->link = $request->link;
        }
        if($request->hide_time)
        {
            $this->validate($request,[
                'hide_time' => 'required',
            ]);
            $popup->hide_time = $request->hide_time;
        }
        $popup->pages = $request->pages;
        $popup->status = $request->status;
        $popup->save();

        return response()->json($popup);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $popup = Popup::find($id);
        if($popup->image)
        {
            unlink(storage_path('app/public/popup/images/'.$popup->image));
        }
        $popup->destroy($id);

        return response()->json($popup);
    }
}
