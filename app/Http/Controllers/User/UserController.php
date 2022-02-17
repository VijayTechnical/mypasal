<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Models\AdPacks;
use App\Models\UserPack;

class UserController extends Controller
{
    public function Dashboard()
    {
        $user = Auth::user();
        return response()->json($user);
    }

    public function editProfile(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required|unique:users,email,'.Auth::user()->id,
            'image' => 'required|mimes:png,jpg',
            'is_phone_hidden' => 'required',
            'phone' => 'required|integer|min:10',
            'address' => 'required'
        ]);
        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($user->profile_photo_path) {
            $this->validate($request, [
                'image' => 'required',
            ]);
            unlink(storage_path('app/public/users/' . $user->profile_photo_path));
        }
        $imageName = Carbon::now()->timestamp . '.' . $request->image->extension();
        $request->image->storeAs('public/users', $imageName);
        $user->profile_photo_path = $imageName;
        $user->address = $request->address;
        $user->is_phone_hidden = $request->is_phone_hidden;
        $user->phone = $request->phone;
        $user->save();

        return response()->json($user);
    }

    public function getAds($user_id)
    {
        $my_ads = UserPack::where('user_id',$user_id)->orderBy('created_at','DESC')->get();
        return response()->json($my_ads);
    }

    public function changePassword(Request $request)
    {
        $this->validate($request,[
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed|different:current_password'
        ]);
        if(Hash::check($request->current_password,Auth::user()->password))
        {
            $user = User::findOrFail(Auth::user()->id);
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json($user);
        }
        else{
            return response()->json('Password does not match our record.');
        }
    }
}
