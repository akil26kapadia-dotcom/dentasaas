<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        return view('blog.index', ['posts' => $this->posts()]);
    }

    public function show(string $slug): View
    {
        $posts = $this->posts();
        $post = $posts->get($slug);
        $file = resource_path("blog/{$slug}.md");

        abort_unless($post && is_file($file), 404);

        return view('blog.show', [
            'post' => $post,
            'html' => Str::markdown(file_get_contents($file), ['html_input' => 'strip', 'allow_unsafe_links' => false]),
            'related' => $posts->except($slug)->take(3),
        ]);
    }

    /** All posts, newest first, keyed by slug. */
    protected function posts()
    {
        return collect(config('blog.posts'))
            ->map(fn ($post, $slug) => $post + ['slug' => $slug])
            ->sortByDesc('date');
    }
}
