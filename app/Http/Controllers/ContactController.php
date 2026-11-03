<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Display the Contact Us page.
     */
    public function index()
    {
        return view('pages.contact');
    }

    /**
     * Handle contact form submission.
     */
    public function submit(Request $request)
    {
        // Honeypot check to prevent automated spam
        if (!empty($request->input('website'))) {
            // Silently redirect bot submissions as if successful
            return redirect()->route('contact')->with('success', 'Thank you for reaching out! We have received your message.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|min:10|max:5000',
        ], [
            'name.required' => 'Please provide your full name.',
            'email.required' => 'Please provide a valid email address.',
            'subject.required' => 'Please select or enter a subject.',
            'message.required' => 'Please write your message (minimum 10 characters).',
            'message.min' => 'Your message must be at least 10 characters long.',
        ]);

        // Log contact message
        Log::info('Contact Form Submission received from ' . $validated['email'], [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'ip' => $request->ip(),
        ]);

        return redirect()->route('contact')
            ->with('success', 'Thank you for contacting CypherLab! Your message has been received. Programmer Brijesh & team will respond to ' . e($validated['email']) . ' as soon as possible.');
    }
}
