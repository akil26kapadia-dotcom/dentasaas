<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Page Not Found - {{ config('app.name', 'DentaSaaS') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <style>body { font-family: 'figtree', sans-serif; }</style>
    </head>
    <body class="antialiased bg-gray-50">
        <div class="min-h-screen flex flex-col items-center justify-center px-4">
            <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden">
                <div style="background-color:#0b1e3d;" class="px-8 py-6 text-center">
                    <div class="text-white font-semibold text-lg mb-1"><i class="fa-solid fa-tooth"></i> DentaSaaS</div>
                    <div class="text-6xl font-bold text-white/90">404</div>
                </div>
                <div class="px-8 py-8 text-center">
                    <h1 class="text-xl font-semibold text-gray-900">Page Not Found</h1>
                    <p class="text-gray-500 mt-2">
                        The page you're looking for doesn't exist or may have been moved.
                    </p>
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center justify-center gap-2 mt-6 px-5 py-2.5 rounded-lg font-medium text-white"
                       style="background-color:#465fff;">
                        <i class="fa-solid fa-house"></i> Go back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
