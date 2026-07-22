<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $page->title }} · {{ $site->company_name }}</title>

        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    </head>
    <body class="min-h-screen bg-white font-sans text-neutral-900 antialiased">
        <header class="border-b border-neutral-200">
            <div class="mx-auto flex max-w-5xl items-center justify-between gap-6 px-6 py-5">
                <a href="{{ $site->previewUrl() }}" class="text-base font-semibold text-neutral-900">
                    {{ $site->company_name }}
                </a>
                @if ($site->pages->isNotEmpty())
                    <nav aria-label="Site" class="flex flex-wrap gap-x-6 gap-y-2 text-sm">
                        @foreach ($site->pages as $navPage)
                            <a
                                href="{{ route('preview.show', $navPage) }}"
                                @class([
                                    'font-medium text-neutral-900 underline decoration-neutral-300 underline-offset-4' => $page->is($navPage),
                                    'text-neutral-500 hover:text-neutral-800' => ! $page->is($navPage),
                                ])
                            >
                                {{ $navPage->title }}
                            </a>
                        @endforeach
                    </nav>
                @endif
            </div>
        </header>

        <main>
            @yield('content')
        </main>

        <footer class="border-t border-neutral-100 bg-neutral-50">
            <div class="mx-auto max-w-5xl px-6 py-8 text-center text-sm text-neutral-500">
                &copy; {{ now()->year }} {{ $site->company_name }}
            </div>
        </footer>
    </body>
</html>
