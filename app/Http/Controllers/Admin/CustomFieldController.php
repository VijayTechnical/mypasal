<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomField;
use Illuminate\Http\Request;

class CustomFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $custom_fields = CustomField::orderBy('created_at','DESC')->with('categories')->get();

        return response()->json($custom_fields);
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
            'type' => 'required',
            'placeholder' => 'required',
            'title' => 'required',
            'is_required' => 'required',
            'status' => 'required',
            'values' => 'required',
            'highlight' => 'required',
        ]);
        $custom_field = new CustomField();
        $custom_field->type = $request->type;
        $custom_field->placeholder = $request->placeholder;
        $custom_field->title = $request->title;
        $custom_field->is_required = $request->is_required;
        $custom_field->status = $request->status;
        $custom_field->values = $request->values;
        $custom_field->highlight = $request->highlight;
        $custom_field->save();

        return response()->json($custom_field);
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
            'type' => 'required',
            'placeholder' => 'required',
            'title' => 'required',
            'is_required' => 'required',
            'status' => 'required',
            'values' => 'required',
            'highlight' => 'required',
        ]);
        $custom_field = CustomField::find($id);
        $custom_field->type = $request->type;
        $custom_field->placeholder = $request->placeholder;
        $custom_field->title = $request->title;
        $custom_field->is_required = $request->is_required;
        $custom_field->status = $request->status;
        $custom_field->values = $request->values;
        $custom_field->highlight = $request->highlight;
        $custom_field->save();

        return response()->json($custom_field);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $custom_field = CustomField::find($id);
        $custom_field->destroy($id);

        return response()->json($custom_field);
    }
}
