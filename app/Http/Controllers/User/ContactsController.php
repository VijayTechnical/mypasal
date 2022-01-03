<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ContactsController extends Controller
{

    public function getMessagesFor(Request $request, $id)
    {

        Message::where('from', $id)->where('to', auth()->user()->id)->update(['read' => true]);

        $messages = Message::where(function ($q) use ($id) {
            $q->where('from', auth()->user()->id);
            $q->where('to', $id);
        })->orWhere(function ($q) use ($id) {
            $q->where('from', $id);
            $q->where('to', auth()->user()->id);
        })
            ->get();


        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $message = Message::create([
            'from' => auth()->user()->id,
            'to' => $request->contact_id,
            'message' => $request->message
        ]);

        return response()->json($message);
    }
}
