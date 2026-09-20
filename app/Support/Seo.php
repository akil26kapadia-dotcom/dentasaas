<?php

namespace App\Support;

use Illuminate\Support\HtmlString;

class Seo
{
    /** Public origin, e.g. https://dentasaas.in (falls back to the current request until the domain is set). */
    public static function origin(): string
    {
        $host = config('dentasaas.canonical_host');

        return $host ? 'https://'.$host : request()->getSchemeAndHttpHost();
    }

    public static function url(string $path = '/'): string
    {
        return rtrim(self::origin(), '/').'/'.ltrim($path, '/');
    }

    /** Canonical URL of the current page: canonical origin + path, no query string. */
    public static function canonical(): string
    {
        $path = request()->getPathInfo();

        return self::url($path === '/' ? '/' : rtrim($path, '/'));
    }

    public static function ogImage(): string
    {
        return self::url('og-image.png');
    }

    /** True on the canonical host in production (or when no canonical host is configured yet, outside production). */
    public static function indexable(): bool
    {
        $canonical = config('dentasaas.canonical_host');

        if (! app()->environment('production')) {
            return false;
        }

        return ! $canonical || strcasecmp(request()->getHost(), $canonical) === 0;
    }

    /**
     * A <script type="application/ld+json"> block, safe to drop straight into a view.
     * @context is added here because Blade would treat a literal @context in a view as a directive.
     */
    public static function jsonLd(array $data): HtmlString
    {
        $json = json_encode(['@context' => 'https://schema.org'] + $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP);

        return new HtmlString('<script type="application/ld+json">'.$json.'</script>');
    }

    /** Breadcrumb structured data from [name => path] pairs, starting with Home. */
    public static function breadcrumbs(array $trail): HtmlString
    {
        $items = [['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => self::url('/')]];
        $position = 2;

        foreach ($trail as $name => $path) {
            $items[] = ['@type' => 'ListItem', 'position' => $position++, 'name' => $name, 'item' => self::url($path)];
        }

        return self::jsonLd(['@type' => 'BreadcrumbList', 'itemListElement' => $items]);
    }

    public static function business(string $key, mixed $default = null): mixed
    {
        return config("dentasaas.business.$key") ?: $default;
    }

    public static function whatsappUrl(?string $text = null): string
    {
        $url = 'https://wa.me/'.self::business('whatsapp', '918488055253');

        return $text ? $url.'?text='.urlencode($text) : $url;
    }
}
