<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Page::orderBy('created_at', 'DESC')->get();

        return response()->json($pages);
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
            'title' => 'required',
            'slug' => 'required|unique:pages',
            'type' => 'required',
            'status' => 'required'
        ]);

        $page = new Page();
        $page->title = $request->title;
        $page->slug = $request->slug;
        $page->type = $request->type;
        if ($request->image) {
            $this->validate($request, [
                'image' => 'required|mimes:png,jpg'
            ]);
            $imageName = Carbon::now()->timestamp . '.' . $request->image->extension();
            $request->image->storeAs('public/page/images', $imageName);
            $page->image = $imageName;
        }
        if ($request->content) {
            $this->validate($request, [
                'content' => 'required'
            ]);
            $page->content = $request->content;
        }
        $page->status = $request->status;
        $page->save();

        return response()->json($page);
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
            'title' => 'required',
            'slug' => 'required|unique:pages,slug,'.$id,
            'type' => 'required',
            'status' => 'required'
        ]);

        $page = Page::find($id);
        $page->title = $request->title;
        $page->slug = $request->slug;
        $page->type = $request->type;
        if ($request->newimage) {
            $this->validate($request, [
                'newimage' => 'required|mimes:png,jpg'
            ]);
            if ($page->image) {
                unlink(storage_path('app/public/page/images/' . $page->image));
            }
            $imageName = Carbon::now()->timestamp . '.' . $request->newimage->extension();
            $request->newimage->storeAs('public/page/images', $imageName);
            $page->image = $imageName;
        }
        else{
            $page->image = $page->image;
        }
        if ($request->content) {
            $this->validate($request, [
                'content' => 'required'
            ]);
            $page->content = $request->content;
        }
        $page->status = $request->status;
        $page->save();

        return response()->json($page);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $page = Page::find($id);
        if ($page->image) {
            unlink(storage_path('app/public/page/images/' . $page->image));
        }
        $page->destroy($id);

        return response()->json($page);
    }
}
