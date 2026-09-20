<?php

use App\Models\Clinic;
use App\Models\Invoice;
use App\Models\Patient;
use App\Services\InvoiceService;

beforeEach(fn () => $this->seed());

// ---------- Public content pages ----------

test('every public page renders with a title, canonical link and description', function (string $path, string $needle) {
    $response = $this->get($path);

    $response->assertOk();
    $response->assertSee($needle);
    $response->assertSee('<link rel="canonical"', false);
    $response->assertSee('name="description"', false);
})->with([
    ['/features', 'Everything your dental clinic needs'],
    ['/faq', 'Frequently asked questions'],
    ['/about', 'DentaSaaS'],
    ['/contact', 'Talk to us'],
    ['/privacy-policy', 'Privacy Policy'],
    ['/terms', 'Terms of Service'],
    ['/refund-policy', 'Refund and Cancellation Policy'],
    ['/blog', 'Guides for running a dental clinic'],
]);

test('every configured feature page renders with structured data', function () {
    foreach (array_keys(config('features.pages')) as $slug) {
        $this->get('/features/'.$slug)
            ->assertOk()
            ->assertSee('FAQPage', false)
            ->assertSee('BreadcrumbList', false);
    }
});

test('unknown feature and blog slugs return 404', function () {
    $this->get('/features/does-not-exist')->assertNotFound();
    $this->get('/blog/does-not-exist')->assertNotFound();
});

test('every configured blog post has a body file and renders as an article', function () {
    foreach (config('blog.posts') as $slug => $post) {
        expect(is_file(resource_path("blog/{$slug}.md")))->toBeTrue("missing resources/blog/{$slug}.md");

        $this->get('/blog/'.$slug)
            ->assertOk()
            ->assertSee($post['title'], false)
            ->assertSee('"@type":"Article"', false);
    }
});

test('home page links to the feature pages and the blog', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee(route('features.show', 'appointment-scheduling'), false)
        ->assertSee(route('blog.index'), false)
        ->assertDontSee('Trusted by dentists across India');
});

test('pricing page ships FAQ structured data', function () {
    $this->get('/pricing')->assertOk()->assertSee('FAQPage', false);
});

// ---------- Sitemap and robots ----------

test('sitemap lists public pages, feature pages and blog posts but no private areas', function () {
    $response = $this->get('/sitemap.xml');

    $response->assertOk();
    expect($response->headers->get('Content-Type'))->toContain('xml');

    $xml = $response->getContent();
    expect($xml)->toContain('/pricing', '/features/appointment-scheduling', '/blog/reduce-dental-appointment-no-shows', '/privacy-policy');
    expect($xml)->not->toContain('/admin')->not->toContain('/dashboard')->not->toContain('/login');
});

test('robots blocks everything outside production', function () {
    $this->get('/robots.txt')->assertOk()->assertSee('Disallow: /', false);
});

test('robots allows crawling and points to the sitemap on the canonical host in production', function () {
    config(['dentasaas.canonical_host' => 'dentasaas.in']);
    $this->app['env'] = 'production';

    $response = $this->get('https://dentasaas.in/robots.txt');

    $response->assertOk();
    $response->assertSee('Sitemap: https://dentasaas.in/sitemap.xml', false);
    $response->assertSee('Disallow: /admin', false);
    $response->assertDontSee("Disallow: /\n", false);
});

// ---------- Canonical host and headers ----------

test('other hosts redirect permanently to the canonical host in production', function () {
    config(['dentasaas.canonical_host' => 'dentasaas.in']);
    $this->app['env'] = 'production';

    $this->get('https://old.example.com/pricing?x=1')
        ->assertStatus(301)
        ->assertRedirect('https://dentasaas.in/pricing?x=1');
});

test('no canonical redirect when the canonical host is not configured', function () {
    config(['dentasaas.canonical_host' => null]);
    $this->app['env'] = 'production';

    $this->get('https://anything.example.com/pricing')->assertOk();
});

test('private areas are marked noindex in headers', function () {
    $this->get('/login')->assertHeader('X-Robots-Tag', 'noindex, nofollow, noarchive');
    $this->get('/pricing')->assertHeaderMissing('X-Robots-Tag');
});

test('security headers are sent and hsts only on the canonical host', function () {
    $this->get('/')
        ->assertHeader('X-Content-Type-Options', 'nosniff')
        ->assertHeaderMissing('Strict-Transport-Security');

    config(['dentasaas.canonical_host' => 'dentasaas.in']);
    $this->get('https://dentasaas.in/')->assertHeader('Strict-Transport-Security');
});

test('request access form is throttled', function () {
    for ($i = 0; $i < 6; $i++) {
        $this->post('/request-access', []);
    }

    $this->post('/request-access', [])->assertStatus(429);
});

// ---------- Super admin seeding ----------

test('super admin seeder does nothing without credentials in config', function () {
    config(['dentasaas.superadmin.email' => null, 'dentasaas.superadmin.password' => null]);
    $before = \App\Models\User::count();

    $this->artisan('db:seed', ['--class' => \Database\Seeders\SuperAdminSeeder::class]);

    expect(\App\Models\User::count())->toBe($before);
});

// ---------- Invoice numbering ----------

test('invoice numbers never repeat after an earlier invoice is deleted', function () {
    $clinic = Clinic::first();
    $patient = Patient::where('clinic_id', $clinic->id)->first() ?? Patient::factory()->create(['clinic_id' => $clinic->id]);
    $service = new InvoiceService;

    $make = fn (string $no) => Invoice::forceCreate([
        'clinic_id' => $clinic->id,
        'patient_id' => $patient->id,
        'patient_name' => 'Test Patient',
        'invoice_no' => $no,
        'invoice_date' => now()->toDateString(),
        'items' => [],
        'subtotal' => 0,
        'tax_amount' => 0,
        'discount_amount' => 0,
        'grand_total' => 0,
    ]);

    $clinic->invoices()->delete();
    $make('INV001');
    $second = $make('INV002');
    $make('INV003');
    $second->delete();

    expect($service->generateInvoiceNo($clinic))->toBe('INV004');
});

// ---------- Structured data ----------

test('every JSON-LD block on public pages is valid JSON with a schema.org context', function (string $path) {
    $html = $this->get($path)->assertOk()->getContent();

    preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $matches);
    expect($matches[1])->not->toBeEmpty();

    foreach ($matches[1] as $json) {
        $data = json_decode($json, true);
        expect($data)->toBeArray()->and($data['@context'] ?? null)->toBe('https://schema.org');
    }
})->with(['/', '/pricing', '/faq', '/about', '/contact', '/features/patient-records', '/blog/dental-clinic-billing-gst-basics']);
