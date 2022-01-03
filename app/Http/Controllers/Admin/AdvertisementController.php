<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Advertisement;
use App\Http\Controllers\Controller;

class AdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $advertisements = Advertisement::orderBy('created_at', 'DESC')->get();

        return response()->json($advertisements);
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
            'type' => 'required',
            'position' => 'required',
            'link' => 'required',
            'status' => 'required'
        ]);

        $advertisement = new Advertisement();
        $advertisement->type = $request->type;
        $advertisement->position = $request->position;
        if ($request->type === 'image') {
            $imageName = Carbon::now()->timestamp . '.' . $request->image->extension();
            $request->image->storeAs('public/advertisement/images', $imageName);
            $advertisement->image = $imageName;
        } else {

            $videoName = Carbon::now()->timestamp . '.' . $request->video->extension();
            $request->video->storeAs('public/advertisement/videos', $videoName);
            $advertisement->video = $videoName;
        }
        $advertisement->link = $request->link;
        $advertisement->status = $request->status;
        $advertisement->save();

        return response()->json($request);
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
            'type' => 'required',
            'position' => 'required',
            'link' => 'required',
            'status' => 'required'
        ]);

        $advertisement = Advertisement::find($id);
        $advertisement->type = $request->type;
        $advertisement->position = $request->position;
        if ($request->type === 'image') {
            if ($request->newimage) {
                $this->validate($request, [
                    'newimage' => 'required',
                ]);
                unlink(storage_path('app/public/advertisement/images/' . $advertisement->image));
                $imageName = Carbon::now()->timestamp . '.' . $request->newimage->extension();
                $request->newimage->storeAs('public/advertisement/images', $imageName);
                $advertisement->image = $imageName;
            } else {
                $advertisement->image = $advertisement->image;
            }
        } else {

            if ($request->newvideo) {
                $this->validate($request, [
                    'newvideo' => 'required',
                ]);
                unlink(storage_path('app/public/advertisement/videos/' . $advertisement->video));
                $videoName = Carbon::now()->timestamp . '.' . $request->newvideo->extension();
                $request->newvideo->storeAs('public/advertisement/videos', $videoName);
                $advertisement->video = $videoName;
            } else {
                $advertisement->video = $advertisement->video;
            }
        }
        $advertisement->link = $request->link;
        $advertisement->status = $request->status;
        $advertisement->save();

        return response()->json($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $advertisement = Advertisement::find($id);
        if ($advertisement->image) {
            unlink(storage_path('app/public/advertisement/images/' . $advertisement->image));
        }
        if ($advertisement->video) {
            unlink(storage_path('app/public/advertisement/videos/' . $advertisement->video));
        }
        $advertisement->destroy($id);

        return response()->json($advertisement);
    }
}
