<?php

namespace App\Http\Controllers\User;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $comments = Comment::where('post_id',$request->id)->with('user')->orderBy('created_at','DESC')->get();

        return response()->json($comments);
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
            'comment' => 'required',
            'post_id' => 'required',
            'answer_id' => 'required',
        ]);
        $comment = new Comment();
        $comment->post_id = $request->post_id;
        $comment->comment = $request->comment;
        $comment->answer_id = $request->answer_id;
        $comment->user_id = $request->user_id;
        $comment->save();

        return response()->json($comment);
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
            'comment' => 'required',
            'post_id' => 'required',
            'answer_id' => 'required',
        ]);
        $comment = Comment::find($id);
        $comment->post_id = $request->post_id;
        $comment->comment = $request->comment;
        $comment->answer_id = $request->answer_id;
        $comment->user_id = $request->Auth::user()->id;
        $comment->save();

        return response()->json($comment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = Comment::find($id);
        $comment->destroy($id);

        return response()->json($comment);
    }
}
