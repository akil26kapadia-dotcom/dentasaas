<?php

namespace App\Http\Controllers;

use App\Support\Seo;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $updated = fn (string $file) => date('Y-m-d', @filemtime(base_path($file)) ?: time());

        $urls = [
            ['/', 'weekly', '1.0', $updated('resources/views/public/home.blade.php')],
            ['/features', 'monthly', '0.9', $updated('config/features.php')],
            ['/pricing', 'weekly', '0.9', $updated('resources/views/public/pricing.blade.php')],
            ['/faq', 'monthly', '0.7', $updated('resources/views/public/faq.blade.php')],
            ['/about', 'yearly', '0.5', $updated('resources/views/public/about.blade.php')],
            ['/contact', 'yearly', '0.5', $updated('resources/views/public/contact.blade.php')],
            ['/request-access', 'monthly', '0.8', $updated('resources/views/public/request-access.blade.php')],
            ['/privacy-policy', 'yearly', '0.3', $updated('resources/views/public/legal/privacy.blade.php')],
            ['/terms', 'yearly', '0.3', $updated('resources/views/public/legal/terms.blade.php')],
            ['/refund-policy', 'yearly', '0.3', $updated('resources/views/public/legal/refund.blade.php')],
            ['/blog', 'weekly', '0.7', $updated('config/blog.php')],
        ];

        foreach (array_keys(config('features.pages', [])) as $slug) {
            $urls[] = ["/features/$slug", 'monthly', '0.8', $updated('config/features.php')];
        }

        foreach (config('blog.posts', []) as $slug => $post) {
            $urls[] = ["/blog/$slug", 'monthly', '0.6', $post['modified'] ?? $post['date']];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        foreach ($urls as [$path, $freq, $priority, $lastmod]) {
            $xml .= '  <url><loc>'.e(Seo::url($path)).'</loc><lastmod>'.$lastmod.'</lastmod><changefreq>'.$freq.'</changefreq><priority>'.$priority.'</priority></url>'."\n";
        }
        $xml .= '</urlset>'."\n";

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function robots(): Response
    {
        if (! Seo::indexable()) {
            $body = "User-agent: *\nDisallow: /\n";
        } else {
            $disallow = [
                '/admin', '/dashboard', '/patients', '/doctors', '/appointments', '/services', '/invoices',
                '/prescriptions', '/treatment-plans', '/analytics', '/settings', '/notifications', '/profile',
                '/login', '/forgot-password', '/reset-password', '/impersonate',
            ];

            $body = "User-agent: *\nAllow: /\n";
            foreach ($disallow as $path) {
                $body .= "Disallow: $path\n";
            }
            $body .= "\nSitemap: ".Seo::url('/sitemap.xml')."\n";
        }

        return response($body, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
