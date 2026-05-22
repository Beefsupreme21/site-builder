@php
    $logoUrl = $site->logo
        ? (\Illuminate\Support\Str::startsWith($site->logo, ['http://', 'https://']) ? $site->logo : asset($site->logo))
        : 'https://placehold.co/240x96/png?text='.urlencode($site->company_name);
@endphp

<header class="relative z-50">
    <nav aria-label="Global" class="flex items-center justify-between border-b-[6px] border-[#1b4896] bg-white p-6 lg:justify-center lg:px-8">
        <div class="flex lg:hidden">
            <button
                type="button"
                class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700"
                @click="mobileOpen = true"
            >
                <span class="sr-only">Open main menu</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-6" aria-hidden="true">
                    <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>
        <div class="hidden gap-x-8 lg:flex lg:items-center">
            <a href="{{ $site->previewUrl() }}" class="-m-1.5 p-1.5">
                <span class="sr-only">{{ $site->company_name }}</span>
                <img src="{{ $logoUrl }}" alt="" class="h-20 w-auto object-contain lg:h-24" />
            </a>
            @if ($site->pages->isNotEmpty())
                <div class="flex gap-x-12">
                    @foreach ($site->pages as $navPage)
                        <a
                            href="{{ route('sites.preview', [$site, $navPage]) }}"
                            @class([
                                'text-base font-semibold text-[#1b4896]' => $currentPage->is($navPage),
                                'text-base font-semibold text-gray-800 hover:text-[#1b4896]' => ! $currentPage->is($navPage),
                            ])
                        >
                            {{ $navPage->title }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="flex lg:hidden">
            <a href="{{ $site->previewUrl() }}" class="-m-1.5 p-1.5">
                <span class="sr-only">{{ $site->company_name }}</span>
                <img src="{{ $logoUrl }}" alt="" class="h-16 w-auto object-contain sm:h-20" />
            </a>
        </div>
    </nav>

    <div
        x-show="mobileOpen"
        x-cloak
        x-transition.opacity
        class="fixed inset-0 z-50 lg:hidden"
        style="display: none;"
    >
        <div class="fixed inset-0 bg-black/40" @click="mobileOpen = false"></div>
        <div class="fixed inset-y-0 right-0 z-50 flex w-full max-w-sm flex-col overflow-y-auto bg-white p-6 shadow-xl sm:ring-1 sm:ring-gray-900/10">
            <div class="flex items-center justify-between">
                <a href="{{ $site->previewUrl() }}" class="-m-1.5 p-1.5">
                    <span class="sr-only">{{ $site->company_name }}</span>
                    <img src="{{ $logoUrl }}" alt="" class="h-12 w-auto object-contain" />
                </a>
                <button type="button" class="-m-2.5 rounded-md p-2.5 text-gray-700" @click="mobileOpen = false">
                    <span class="sr-only">Close menu</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-6" aria-hidden="true">
                        <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
            @if ($site->pages->isNotEmpty())
                <div class="mt-6 flow-root">
                    <div class="-my-6 divide-y divide-gray-500/10">
                        <div class="space-y-2 py-6">
                            @foreach ($site->pages as $navPage)
                                <a
                                    href="{{ route('sites.preview', [$site, $navPage]) }}"
                                    @class([
                                        '-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-[#1b4896] bg-blue-50' => $currentPage->is($navPage),
                                        '-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50' => ! $currentPage->is($navPage),
                                    ])
                                    @click="mobileOpen = false"
                                >
                                    {{ $navPage->title }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</header>
