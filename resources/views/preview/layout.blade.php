<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $page->title }} · {{ $site->company_name }}</title>

        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>

        <style>
            :root {
                --primary-500: {{ $site->primary_color }};
                --primary-400: color-mix(in srgb, var(--primary-500) 60%, white);
                --primary-300: color-mix(in srgb, var(--primary-500) 45%, white);
                --primary-200: color-mix(in srgb, var(--primary-500) 30%, white);
                --primary-100: color-mix(in srgb, var(--primary-500) 15%, white);
                --primary-600: color-mix(in srgb, var(--primary-500) 85%, black);
                --primary-700: color-mix(in srgb, var(--primary-500) 70%, black);
                --primary-800: color-mix(in srgb, var(--primary-500) 55%, black);
                --primary-900: color-mix(in srgb, var(--primary-500) 40%, black);

                --secondary-500: {{ $site->secondary_color }};
                --secondary-400: color-mix(in srgb, var(--secondary-500) 60%, white);
                --secondary-300: color-mix(in srgb, var(--secondary-500) 45%, white);
                --secondary-200: color-mix(in srgb, var(--secondary-500) 30%, white);
                --secondary-100: color-mix(in srgb, var(--secondary-500) 15%, white);
                --secondary-600: color-mix(in srgb, var(--secondary-500) 85%, black);
                --secondary-700: color-mix(in srgb, var(--secondary-500) 70%, black);
                --secondary-800: color-mix(in srgb, var(--secondary-500) 55%, black);
                --secondary-900: color-mix(in srgb, var(--secondary-500) 40%, black);
            }
        </style>
    </head>
    <body class="min-h-screen bg-white font-sans text-neutral-900 antialiased">
        @yield('content')
    </body>
</html>
