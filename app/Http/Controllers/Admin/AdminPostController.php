<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\Setup;
use App\Models\UserPack;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminPostController extends Controller
{
    public function index()
    {
        $posts = Post::orderBy('created_at','DESC')->get();

        return response()->json($posts);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'pack_type' => 'required|integer',
            'title' => 'required',
            'slug' => 'required|unique:posts',
            'description' => 'required',
            'selling_price' => 'required',
            'is_negotiable' => 'required',
            'location' => 'required',
            'home_delivery' => 'required',
            'user_id' => 'required',
            'delivery_location' => 'required',
            'expire_date' => 'required',
            'is_sold' => 'required',
            'custom_fields' => 'required',
            'category_id' => 'required',
            'condition' => 'required',
            'thumbnail' => 'required|mimes:jpg,png',
            'featured' => 'required',
            'delivery_charge' => 'required',
            'youtube_link' => 'required',
            'image' => 'required|mimes:jpg,png'
        ]);

        if ($request->pack_type === 0) {

            $posts = Post::where('user_id', $request->user_id)->count();
            $setup = Setup::find(1);
            if ($posts > $setup->free_posts) {
                return response()->json('Free Post limit is exceed! please buy pack to post!!');
            }
        }

        $post = new Post();
        $post->title = $request->title;
        $post->slug = $request->slug;
        $post->description = $request->description;
        $post->selling_price = $request->selling_price;
        $post->is_negotiable = $request->is_negotiable;
        $post->location = $request->location;
        $post->home_delivery = $request->home_delivery;
        $post->delivery_location = $request->delivery_location;
        $post->expire_date = $request->expire_date;
        $post->is_sold = $request->is_sold;
        $post->status = 0;
        $post->custom_fields = $request->custom_fields;
        $post->user_id = $request->user_id;
        $post->category_id = $request->category_id;
        $post->condition = $request->condition;
        $post->featured = $request->featured;
        $post->delivery_charge = $request->delivery_charge;
        $post->youtube_link = $request->youtube_link;
        if ($request->image) {
            $this->validate($request, [
                'image' => 'required|mimes:png,jpg',
            ]);
            $imageName = Carbon::now()->timestamp . '.' . $request->image->extension();
            $request->image->storeAs('public/post/images', $imageName);
            $post->image = $imageName;
        }
        if ($request->thumbnail) {
            $this->validate($request, [
                'thumbnail' => 'required|mimes:png,jpg',
            ]);
            $thumbnailName = Carbon::now()->timestamp . '.' . $request->thumbnail->extension();
            $request->thumbnail->storeAs('public/post/thumbnails', $thumbnailName);
            $post->thumbnail = $thumbnailName;
        }
        $post->views = 0;
        if ($request->pack_type != 0) {
            $pack = UserPack::find($request->pack_type);
            $post->tag = $pack->type;
            $pack->size = $pack->size - 1;
            $pack->save();
        }
        $post->save();

        return response()->json($post);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'pack_type' => 'required|integer',
            'title' => 'required',
            'slug' => 'required|unique:posts,slug,' . $id,
            'description' => 'required',
            'selling_price' => 'required',
            'is_negotiable' => 'required',
            'location' => 'required',
            'home_delivery' => 'required',
            'user_id' => 'required',
            'delivery_location' => 'required',
            'expire_date' => 'required',
            'is_sold' => 'required',
            'custom_fields' => 'required',
            'category_id' => 'required',
            'condition' => 'required',
            'featured' => 'required',
            'delivery_charge' => 'required',
            'youtube_link' => 'required',
        ]);

        if ($request->pack_type === 0) {

            $posts = Post::where('user_id', $request->user_id)->count();
            $setup = Setup::find(1);
            if ($posts > $setup->free_posts) {
                return response()->json('Free Post limit is exceed! please buy pack to post!!');
            }
        }

        $post = Post::find($id);
        $post->title = $request->title;
        $post->slug = $request->slug;
        $post->description = $request->description;
        $post->selling_price = $request->selling_price;
        $post->is_negotiable = $request->is_negotiable;
        $post->location = $request->location;
        $post->home_delivery = $request->home_delivery;
        $post->delivery_location = $request->delivery_location;
        $post->expire_date = $request->expire_date;
        $post->is_sold = $request->is_sold;
        $post->status = 0;
        $post->custom_fields = $request->custom_fields;
        $post->user_id = $request->user_id;
        $post->category_id = $request->category_id;
        $post->condition = $request->condition;
        $post->featured = $request->featured;
        $post->delivery_charge = $request->delivery_charge;
        $post->youtube_link = $request->youtube_link;
        if ($request->newimage) {
            $this->validate($request, [
                'newimage' => 'required|mimes:png,jpg',
            ]);
            if ($post->image) {
                unlink(storage_path('app/public/post/images/' . $post->image));
            }
            $imageName = Carbon::now()->timestamp . '.' . $request->newimage->extension();
            $request->newimage->storeAs('public/post/images', $imageName);
            $post->image = $imageName;
        } else {
            $post->image = $post->image;
        }
        if ($request->newthumbnail) {
            $this->validate($request, [
                'newthumbnail' => 'required|mimes:png,jpg',
            ]);
            if ($post->thumbnail) {
                unlink(storage_path('app/public/post/thumbnails/' . $post->thumbnail));
            }
            $thumbnailName = Carbon::now()->timestamp . '.' . $request->newthumbnail->extension();
            $request->newthumbnail->storeAs('public/post/thumbnails', $thumbnailName);
            $post->thumbnail = $thumbnailName;
        } else {
            $post->thumbnail = $post->thumbnail;
        }
        $post->views = $post->views;
        if ($request->pack_type != 0) {
            $pack = UserPack::find($request->pack_type);
            $post->tag = $pack->type;
        }
        $post->save();

        return response()->json($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        if ($post->image) {
            unlink(storage_path('app/public/post/images/' . $post->image));
        }
        if ($post->thumbnail) {
            unlink(storage_path('app/public/post/thumbnails/' . $post->thumbnail));
        }
        $post->comments()->delete();
        $post->destroy($id);

        return response()->json($post);
    }
}
