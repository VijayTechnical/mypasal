<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus = Menu::orderBy('created_at','DESC')->with('menu','child')->get();

        return response()->json($menus);
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
            'status' => 'required',
            'url' => 'required'
        ]);
        $menu = new Menu();
        $menu->name = $request->name;
        $menu->status = $request->status;
        $menu->url = $request->url;
        if($request->menu_id)
        {
            $this->validate($request,[
                'menu_id' => 'required',
            ]);
            $menu->menu_id = $request->menu_id;
        }
        $menu->save();

        return response()->json($menu);
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
            'status' => 'required',
            'url' => 'required'
        ]);
        $menu = Menu::find($id);
        $menu->name = $request->name;
        $menu->status = $request->status;
        $menu->url = $request->url;
        if($request->menu_id)
        {
            $this->validate($request,[
                'menu_id' => 'required',
            ]);
            $menu->menu_id = $request->menu_id;
        }
        $menu->save();

        return response()->json($menu);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $menu = Menu::find($id);
        $menu->destroy($id);
        return response()->json($menu);
    }
}
