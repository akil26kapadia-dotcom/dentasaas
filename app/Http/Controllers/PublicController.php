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
            'featurePages' => config('features.pages'),
            'latestPosts' => collect(config('blog.posts'))
                ->map(fn ($post, $slug) => $post + ['slug' => $slug])
                ->sortByDesc('date')
                ->take(3),
        ]);
    }

    public function pricing(): View
    {
        return view('public.pricing', [
            'plans' => Plan::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function features(): View
    {
        return view('public.features', ['pages' => config('features.pages')]);
    }

    public function feature(string $slug): View
    {
        $pages = config('features.pages');

        abort_unless(isset($pages[$slug]), 404);

        return view('public.feature', [
            'slug' => $slug,
            'page' => $pages[$slug],
            'others' => collect($pages)->except($slug),
        ]);
    }

    public function faq(): View
    {
        return view('public.faq', ['faqs' => config('faq.items')]);
    }

    public function about(): View
    {
        return view('public.about');
    }

    public function contact(): View
    {
        return view('public.contact');
    }

    public function privacy(): View
    {
        return view('public.legal.privacy');
    }

    public function terms(): View
    {
        return view('public.legal.terms');
    }

    public function refund(): View
    {
        return view('public.legal.refund');
    }
}
