<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('marketing.contact');
    }

    public function send(Request $request)
    {
        $request->validate([
            'name'    => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email'],
            'subject' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string', 'min:20'],
        ]);

        // In production this would queue a mail
        // Mail::to('support@skynetug.com')->send(new ContactMail($request->all()));

        return back()->with('success', 'Your message has been sent. We\'ll get back to you within 24 hours.');
    }
}
