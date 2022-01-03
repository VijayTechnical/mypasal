<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faqs = Faq::orderBy('created_at','DESC')->get();
        return response()->json($faqs);
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
            'question' => 'required',
            'answer' => 'required',
            'status' => 'required',
            'position' => 'required',
            'type' => 'required',
        ]);
        $faq = new Faq();
        $faq->question = $request->question;
        $faq->status = $request->status;
        $faq->answer = $request->answer;
        $faq->position = $request->position;
        $faq->type = $request->type;
        $faq->save();

        return response()->json($faq);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FaqController  $faqController
     * @return \Illuminate\Http\Response
     */
    public function show(FaqController $faqController)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FaqController  $faqController
     * @return \Illuminate\Http\Response
     */
    public function edit(FaqController $faqController)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FaqController  $faqController
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'question' => 'required',
            'answer' => 'required',
            'status' => 'required',
            'position' => 'required',
            'type' => 'required',
        ]);
        $faq = Faq::find($id);
        $faq->question = $request->question;
        $faq->status = $request->status;
        $faq->answer = $request->answer;
        $faq->position = $request->position;
        $faq->type = $request->type;
        $faq->save();

        return response()->json($faq);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FaqController  $faqController
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $faq = Faq::find($id);
        $faq->destroy($id);

        return response()->json($faq);
    }
}
