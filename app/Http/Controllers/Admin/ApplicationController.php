<?php

namespace App\Http\Controllers\Admin;

use App\Models\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = Application::orderBy('created_at','DESC')->with('vacancy')->get();

        return response()->json($applications);
    }

    public function show($id)
    {
        $application = Application::where('id',$id)->with('vacancy')->first();
        return response()->json($application);
    }

    public function destroy($id)
    {
        $application = Application::find($id);
        if($application->resume)
        {
            unlink(storage_path('app/public/applications/' . $application->resume));
        }
        $application->destroy($id);

        return response()->json($application);
    }
}
