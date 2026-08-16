<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PublicController extends Controller
{
    public function home(): View
    {
        return view('public.home');
    }

    public function pricing(): View
    {
        return view('public.pricing', [
            'plans' => config('plans'),
        ]);
    }
}
