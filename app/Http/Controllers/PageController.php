<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display About Us page.
     */
    public function about()
    {
        return view('pages.about');
    }

    /**
     * Display Contact page.
     */
    public function contact()
    {
        return view('pages.contact');
    }

    /**
     * Process contact form submission.
     */
    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // Here you could send an email notification
        // Mail::to('support@digitalabs.id')->send(new ContactMessage($validated));

        return back()->with('success', 'Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.');
    }

    /**
     * Display Privacy Policy page.
     */
    public function privacy()
    {
        return view('pages.privacy');
    }

    /**
     * Display Terms & Conditions page.
     */
    public function terms()
    {
        return view('pages.terms');
    }

    /**
     * Display Refund Policy page.
     */
    public function refund()
    {
        return view('pages.refund');
    }
}
