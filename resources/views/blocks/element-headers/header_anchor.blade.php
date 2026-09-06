@php
    $companyName = $site->company_name ?? 'Ridgeline Coffee';
    $logoUrl = isset($site) ? \App\Support\SiteLogo::url($site->logo) : null;
@endphp

<header class="sticky top-0 z-40 border-b border-stone-200 bg-white/95 backdrop-blur">
    <div class="mx-auto flex max-w-6xl items-center justify-between gap-8 px-6 py-4">
        <a href="#top" class="flex items-center gap-3">
            @if ($logoUrl)
                <img src="{{ $logoUrl }}" alt="{{ $companyName }}" class="h-8 w-auto" />
            @else
                <span class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-[var(--primary-700)] text-xs font-semibold tracking-wider text-white">
                    RC
                </span>
            @endif
            <span class="text-base font-semibold tracking-tight text-stone-900">
                {{ $companyName }}
            </span>
        </a>

        <nav aria-label="Sections" class="hidden items-baseline gap-8 md:flex">
            <a href="#menu" class="text-sm font-medium text-stone-600 hover:text-stone-900">
                What we pour
            </a>
            <a href="#story" class="text-sm font-medium text-stone-600 hover:text-stone-900">
                Our story
            </a>
            <a href="#visit" class="text-sm font-medium text-stone-600 hover:text-stone-900">
                Visit
            </a>
        </nav>

        <div class="flex items-center gap-6">
            <a href="tel:+15035550148" class="hidden text-sm font-medium text-stone-600 hover:text-stone-900 sm:block">
                (503) 555-0148
            </a>
            <a href="#visit" class="rounded-lg bg-[var(--primary-700)] px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[var(--primary-800)]">
                Find us
            </a>
        </div>
    </div>
</header>
