<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\View\View;

class PublicController extends Controller
{
    public function home(): View
    {
        return view('public.home', [
            'plans' => Plan::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function pricing(): View
    {
        return view('public.pricing', [
            'plans' => Plan::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }
}
