<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AffiliatePublicController extends Controller
{
    /**
     * Display affiliate program landing page
     */
    public function index()
    {
        return view('affiliate.index');
    }
}
