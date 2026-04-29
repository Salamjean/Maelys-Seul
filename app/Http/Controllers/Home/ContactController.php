<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceRequest;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('home.contact');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'phone' => 'nullable|string|max:20',
        ]);

        MaintenanceRequest::create([
            'user_id' => auth()->id(), // null if guest
            'guest_name' => auth()->check() ? null : $request->name,
            'guest_email' => auth()->check() ? null : $request->email,
            'guest_phone' => auth()->check() ? null : $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'category' => 'question',
            'status' => 'pending',
        ]);

        return redirect()->route('home')->with('success', 'Votre message a bien été envoyé. Notre équipe vous contactera sous peu.');
    }
}
