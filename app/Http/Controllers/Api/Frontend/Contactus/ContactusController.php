<?php

namespace App\Http\Controllers\Api\Frontend\Contactus;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ContactusController extends Controller
{
  public function store(Request $request)
{
    // Validate request data
    $validated = $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|max:255',
        'phone'    => 'nullable|string|max:20',
        'postcode' => 'nullable|string|max:20',
        'message'  => 'required|string',
    ]);

    // Save contact to database
    $contact = Contact::create($validated);

    // Get email from .env file
    $recipientEmail = env('CONTACT_RECEIVER_EMAIL', null);

    if ($recipientEmail) {
        // Send plain text email
        Mail::raw(
            "You have received a new contact form submission:\n\n".
            "Name: {$contact->name}\n".
            "Email: {$contact->email}\n".
            "Phone: {$contact->phone}\n".
            "Postcode: {$contact->postcode}\n".
            "Message:\n{$contact->message}\n",
            function ($message) use ($recipientEmail) {
                $message->to($recipientEmail)
                        ->subject('New Contact Form Submission');
            }
        );
    }

    // Return success response
    return response()->json([
        'status'  => true,
        'message' => 'Contact submitted successfully and email sent',
        'data'    => $contact
    ], 201);
}

}
