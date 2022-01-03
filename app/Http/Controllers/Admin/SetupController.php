<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Setup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SetupController extends Controller
{
    public function index()
    {
        $setup = Setup::find(1);

        return response()->json($setup);
    }

    public function update(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'facebook' => 'required',
            'twitter' => 'required',
            'instagram' => 'required',
            'google' => 'required',
            'youtube' => 'required',
            'introduction' => 'required',
            'map' => 'required',
            'location' => 'required',
            'slogan' => 'required',
            'free_posts' => 'required'
        ]);
        $setup = Setup::find(1);
        if(!$setup)
        {
            $setup = new Setup();
        }
        $setup->name = $request->name;
        if($request->newlogo)
        {
            $this->validate($request,[
                'newlogo' => 'required|mimes:png,jpg,svg'
            ]);
            if($setup->logo)
            {
                unlink(storage_path('app/public/setup/logos/'.$setup->logo));
            }
            $logoName = Carbon::now()->timestamp . '.' . $request->newlogo->extension();
            $request->newlogo->storeAs('public/setup/logos', $logoName);
            $setup->logo = $logoName;
        }
        else{
            $setup->logo = $setup->logo;
        }
        if($request->newfavicon)
        {
            $this->validate($request,[
                'newfavicon' => 'required|mimes:png,jpg,svg'
            ]);
            if($setup->favicon)
            {
                unlink(storage_path('app/public/setup/favicons/'.$setup->favicon));
            }
            $faviconName = Carbon::now()->timestamp . '.' . $request->newfavicon->extension();
            $request->newfavicon->storeAs('public/setup/favicons', $faviconName);
            $setup->favicon = $faviconName;
        }
        else{
            $setup->favicon = $setup->favicon;
        }
        $setup->email = $request->email;
        $setup->phone = $request->phone;
        $setup->facebook = $request->facebook;
        $setup->twitter = $request->twitter;
        $setup->instagram = $request->instagram;
        $setup->google = $request->google;
        $setup->youtube = $request->youtube;
        $setup->introduction = $request->introduction;
        $setup->map = $request->map;
        $setup->location = $request->location;
        $setup->slogan = $request->slogan;
        $setup->free_posts = $request->free_posts;
        $setup->save();

        return response()->json($setup);
    }
}
