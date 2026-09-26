<?php

return [

    /*
     * The public host the site lives on, e.g. "dentasaas.in". When set in production, every
     * other host (the old subdomain, www.) is 301-redirected here and canonical URLs, the
     * sitemap and robots.txt all use it. Leave empty until the domain is live.
     */
    'canonical_host' => env('APP_CANONICAL_HOST'),

    /*
     * Credentials for `php artisan db:seed --class=SuperAdminSeeder`. Nothing is hardcoded:
     * the seeder refuses to run unless both are set.
     */
    'superadmin' => [
        'name' => env('SUPERADMIN_NAME', 'Super Admin'),
        'email' => env('SUPERADMIN_EMAIL'),
        'password' => env('SUPERADMIN_PASSWORD'),
    ],

    /*
     * Business details shown on the About, Contact and legal pages. Optional fields are hidden
     * when empty, so fill these in .env as they become available.
     */
    'business' => [
        'name' => env('BUSINESS_NAME', 'DentaSaaS'),
        'legal_name' => env('BUSINESS_LEGAL_NAME'),
        'email' => env('BUSINESS_EMAIL'),
        'phone' => env('BUSINESS_PHONE', '+91 99604 57501'),
        'whatsapp' => env('BUSINESS_WHATSAPP', '919960457501'),
        'address' => env('BUSINESS_ADDRESS'),
        'grievance_officer' => env('BUSINESS_GRIEVANCE_OFFICER'),
        // How many days after a first paid period a refund can be requested (see /refund-policy).
        'refund_days' => (int) env('REFUND_DAYS', 7),
    ],

    /*
     * Search-engine and analytics integrations. Each snippet is only output when its value is set.
     */
    'seo' => [
        'google_site_verification' => env('GOOGLE_SITE_VERIFICATION'),
        'bing_site_verification' => env('BING_SITE_VERIFICATION'),
        'ga4_measurement_id' => env('GA4_MEASUREMENT_ID'),
    ],

];
