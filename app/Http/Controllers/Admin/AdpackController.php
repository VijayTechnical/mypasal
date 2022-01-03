<?php

namespace App\Http\Controllers\Admin;

use App\Models\AdPacks;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdpackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $adpacks = AdPacks::orderBy('created_at', 'DESC')->with('categories')->get();
        return response()->json($adpacks);
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
            'type' => 'required',
            'size' => 'required',
            'valid' => 'required',
            'valid_parameter' => 'required',
            'price' => 'required',
            'description' => 'required',
            'discount' => 'required',
            'status' => 'required'
        ]);

        $adpack = new AdPacks();
        $adpack->type = $request->type;
        $adpack->size = $request->size;
        $adpack->valid = $request->valid;
        $adpack->valid_parameter = $request->valid_parameter;
        $adpack->price = $request->price;
        $adpack->description = $request->description;
        $adpack->discount = $request->discount;
        $adpack->status = $request->status;
        $adpack->save();
        $adpack->categories()->sync($request->categories);

        return response()->json($adpack);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'type' => 'required',
            'size' => 'required',
            'valid' => 'required',
            'valid_parameter' => 'required',
            'price' => 'required',
            'description' => 'required',
            'discount' => 'required',
            'status' => 'required'
        ]);

        $adpack = AdPacks::findOrFail($id);
        $adpack->type = $request->type;
        $adpack->size = $request->size;
        $adpack->valid = $request->valid;
        $adpack->valid_parameter = $request->valid_parameter;
        $adpack->price = $request->price;
        $adpack->description = $request->description;
        $adpack->discount = $request->discount;
        $adpack->status = $request->status;
        $adpack->save();
        $adpack->categories()->sync($request->categories);

        return response()->json($adpack);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $adpack = Adpacks::findOrFail($id);
        $adpack->categories()->delete();
        return response()->json($adpack);
    }
}
