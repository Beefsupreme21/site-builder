<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $sitePage->title }} · {{ $site->company_name }}</title>
        @vite(['resources/css/app.css'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>
        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>
    </head>
    <body x-data="{ mobileOpen: false }">
        <div class="bg-white">
            @include('sites.partials.alternate-nav')

            @yield('content')

            <footer class="bg-[#2f2e2e]">
                <div class="mx-auto max-w-7xl overflow-hidden px-6 py-20 sm:py-24 lg:px-8">
                    @if ($site->pages->isNotEmpty())
                        <nav aria-label="Footer" class="-mb-6 flex flex-wrap justify-center gap-x-12 gap-y-3 text-sm/6">
                            @foreach ($site->pages as $navPage)
                                <a
                                    href="{{ route('sites.preview', [$site, $navPage]) }}"
                                    @class([
                                        'text-white' => $currentPage->is($navPage),
                                        'text-gray-400 hover:text-white' => ! $currentPage->is($navPage),
                                    ])
                                >
                                    {{ $navPage->title }}
                                </a>
                            @endforeach
                        </nav>
                    @endif
                    <p class="mt-10 text-center text-sm/6 text-gray-400">&copy; {{ now()->year }} {{ $site->company_name }}. All rights reserved.</p>
                </div>
            </footer>
        </div>
    </body>
</html>
