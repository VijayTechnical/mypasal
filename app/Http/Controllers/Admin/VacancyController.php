<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vacancy;
use Illuminate\Http\Request;

class VacancyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vacancies = Vacancy::orderBy('created_at','DESC')->get();
        return response()->json($vacancies);
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
            'slug' => 'required',
            'description' => 'required',
            'requirement' => 'required',
            'education' => 'required',
            'qualification' => 'required',
            'experience' => 'required',
            'status' => 'required',
            'status' => 'required',
        ]);

        $vacancy = new Vacancy();
        $vacancy->name = $request->name;
        $vacancy->slug = $request->slug;
        $vacancy->description = $request->description;
        if($request->open_position)
        {
            $this->validate($request,[
                'open_position' => 'required',
            ]);
            $vacancy->open_position = $request->open_position;
        }
        $vacancy->requirement = $request->requirement;
        $vacancy->education = $request->education;
        $vacancy->qualification = $request->qualification;
        $vacancy->experience = $request->experience;
        $vacancy->status = $request->status;
        $vacancy->expire_date = $request->expire_date;
        $vacancy->save();

        return response()->json($vacancy);

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
            'slug' => 'required',
            'description' => 'required',
            'requirement' => 'required',
            'education' => 'required',
            'qualification' => 'required',
            'experience' => 'required',
            'status' => 'required',
            'status' => 'required',
        ]);

        $vacancy = Vacancy::find($id);
        $vacancy->name = $request->name;
        $vacancy->slug = $request->slug;
        $vacancy->description = $request->description;
        if($request->open_position)
        {
            $this->validate($request,[
                'open_position' => 'required',
            ]);
            $vacancy->open_position = $request->open_position;
        }
        $vacancy->requirement = $request->requirement;
        $vacancy->education = $request->education;
        $vacancy->qualification = $request->qualification;
        $vacancy->experience = $request->experience;
        $vacancy->status = $request->status;
        $vacancy->expire_date = $request->expire_date;
        $vacancy->save();

        return response()->json($vacancy);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vacancy = Vacancy::find($id);
        $vacancy->destroy($id);

        return response()->json($vacancy);
    }
}
