<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::orderBy('created_at','DESC')->get();
        return response()->json($contacts);
    }

    public function show($id)
    {
        $contact = Contact::find($id);
        return response()->json($contact);
    }

    public function destroy($id)
    {
        $contact = Contact::find($id);
        $contact->destroy($id);

        return response()->json($contact);
    }
}
