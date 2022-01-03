<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::orderBy('created_at', 'DESC')->get();

        return response()->json($articles);
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
            'slug' => 'required|unique:articles',
            'publish' => 'required',
            'description' => 'required',
            'photo' => 'required|mimes:jpg,png,svg',
            'author' => 'required'
        ]);

        $article = new Article();
        $article->title = $request->title;
        $article->slug = $request->slug;
        $article->description = $request->description;
        $photoName = Carbon::now()->timestamp . '.' . $request->photo->extension();
        $request->photo->storeAs('public/article/photos', $photoName);
        $article->photo = $photoName;
        $article->publish = $request->publish;
        $article->author = $request->author;
        $article->save();

        return response()->json($article);
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
            'slug' => 'required|unique:articles,slug,' . $id,
            'publish' => 'required',
            'description' => 'required',
            'author' => 'required'
        ]);

        $article =  Article::find($id);
        $article->title = $request->title;
        $article->slug = $request->slug;
        $article->description = $request->description;
        if ($request->newphoto) {
            $this->validate($request, [
                'newphoto' => 'required|mimes:jpg,png,svg',
            ]);
            if ($article->photo) {
                unlink(storage_path('app/public/article/photos/' . $article->photo));
            }
            $photoName = Carbon::now()->timestamp . '.' . $request->newphoto->extension();
            $request->newphoto->storeAs('public/article/photos', $photoName);
            $article->photo = $photoName;
        } else {
            $article->photo = $article->photo;
        }
        $article->publish = $request->publish;
        $article->author = $request->author;
        $article->save();

        return response()->json($article);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article =  Article::find($id);
        if ($article->photo) {
            unlink(storage_path('app/public/article/photos/' . $article->photo));
        }
        $article->destroy($id);
        return response()->json($article);
    }
}
