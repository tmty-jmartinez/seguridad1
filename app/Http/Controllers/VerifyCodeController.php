<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VerifyCodeController extends Controller
{
    /**
     * Display the verify page.
     *
     * Cache is disabled to prevent the 'verify-2fa' page from being stored.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()
            ->view('verify-2fa')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');
    }
}
