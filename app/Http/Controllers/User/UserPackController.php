<?php

namespace App\Http\Controllers\User;

use App\Models\UserPack;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserPackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_packs = UserPack::where('user_id',Auth::user()->id)->with('ad_pack')->get();

        return response()->json($user_packs);

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
            'ad_pack_id' => 'required|integer',
            'type' => 'required',
            'valid' => 'required',
            'valid_parameter' => 'required',
            'size' => 'required',
            'price' => 'required',
        ]);

        $user_pack = new UserPack();
        $user_pack->user_id = Auth::user()->id;
        $user_pack->ad_pack_id = $request->ad_pack_id;
        $user_pack->type = $request->type;
        $user_pack->valid = $request->valid;
        $user_pack->valid_parameter = $request->valid_parameter;
        $user_pack->size = $request->size;
        $user_pack->price = $request->price;
        $user_pack->payment_status = $request->payment_status;
        $user_pack->ref = $request->ref;
        $user_pack->save();

        return response()->json($user_pack);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user_pack = UserPack::where('id',$id)->orWhere('user_id',Auth::user()->id)->with('ad_pack')->first();

        return response()->json($user_pack);
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
            'ad_pack_id' => 'required|integer',
            'type' => 'required',
            'valid' => 'required',
            'valid_parameter' => 'required',
            'size' => 'required',
            'price' => 'required',
            'payment_status' => 'required',
            'ref' => 'required'
        ]);

        $user_pack = UserPack::find($id);
        $user_pack->user_id = Auth::user()->id;
        $user_pack->ad_pack_id = $request->ad_pack_id;
        $user_pack->type = $request->type;
        $user_pack->valid = $request->valid;
        $user_pack->valid_parameter = $request->valid_parameter;
        $user_pack->size = $request->size;
        $user_pack->price = $request->price;
        $user_pack->payment_status = $request->payment_status;
        $user_pack->ref = $request->ref;
        $user_pack->save();

        return response()->json($user_pack);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user_pack = UserPack::find($id);
        if($user_pack)
        {
            $user_pack->destroy($id);
            return response()->json($user_pack);
        }
        return response()->json([
            'message' => 'No pack found!!!'
        ,404]);
    }
}
