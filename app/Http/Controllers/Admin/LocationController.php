<?php

namespace App\Http\Controllers\Admin;

use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations = Location::orderby('created_at', 'DESC')->with('location')->get();
        return response()->json($locations);
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
            'name' => 'required',
            'show_in_home' => 'required|integer',
            'status' => 'required|integer'
        ]);

        $location = new Location();
        $location->name = $request->name;
        if($request->lat)
        {
            $this->validate($request,[
                'lat' => 'required'
            ]);
            $location->lat = $request->lat;
        }
        if($request->lang)
        {
            $this->validate($request,[
                'lang' => 'required'
            ]);
            $location->lang = $request->lang;
        }
        $location->status = $request->status;
        $location->show_in_home = $request->show_in_home;
        if($request->parent_id)
        {
            $this->validate($request,[
                'parent_id' => 'required'
            ]);
            $location->parent_id = $request->parent_id;
        }
        $location->save();

        return response()->json($location);

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
            'name' => 'required',
            'show_in_home' => 'required|integer',
            'status' => 'required|integer'
        ]);

        $location = Location::find($id);
        $location->name = $request->name;
        if($request->lat)
        {
            $this->validate($request,[
                'lat' => 'required'
            ]);
            $location->lat = $request->lat;
        }
        if($request->lang)
        {
            $this->validate($request,[
                'lang' => 'required'
            ]);
            $location->lang = $request->lang;
        }
        $location->status = $request->status;
        $location->show_in_home = $request->show_in_home;
        if($request->parent_id)
        {
            $this->validate($request,[
                'parent_id' => 'required'
            ]);
            $location->parent_id = $request->parent_id;
        }
        $location->save();

        return response()->json($location);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $location = Location::find($id);
        $location->destroy($id);

        return response()->json($location);
    }
}
