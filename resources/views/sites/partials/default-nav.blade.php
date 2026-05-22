<nav aria-label="Site" class="border-b border-neutral-200 bg-white">
    <div class="mx-auto flex max-w-3xl flex-wrap items-center justify-between gap-4 px-4 py-4 sm:px-6">
        <a
            href="{{ $site->previewUrl() }}"
            class="text-sm font-semibold text-neutral-900 hover:text-neutral-700"
        >
            {{ $site->company_name }}
        </a>
        @if ($site->pages->isNotEmpty())
            <div class="flex flex-wrap gap-x-6 gap-y-2 text-sm">
                @foreach ($site->pages as $navPage)
                    <a
                        href="{{ route('sites.preview', [$site, $navPage]) }}"
                        @class([
                            'font-medium text-neutral-900 underline decoration-neutral-300 underline-offset-4' => $currentPage->is($navPage),
                            'text-neutral-500 hover:text-neutral-800' => ! $currentPage->is($navPage),
                        ])
                    >
                        {{ $navPage->title }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</nav>
