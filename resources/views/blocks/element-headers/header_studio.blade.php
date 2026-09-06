@php
    $companyName = $site->company_name ?? 'Willow';
    $logoUrl = isset($site) ? \App\Support\SiteLogo::url($site->logo) : null;
@endphp

<header class="border-b border-zinc-200 bg-white">
    <div class="mx-auto flex max-w-6xl items-center justify-between gap-8 px-6 py-5">
        <a href="/" class="flex items-center gap-3">
            @if ($logoUrl)
                <img src="{{ $logoUrl }}" alt="{{ $companyName }}" class="h-8 w-auto" />
            @endif
            <span @class(['font-serif text-xl tracking-tight text-zinc-900', 'sr-only' => $logoUrl])>
                {{ $companyName }}
            </span>
        </a>

        <nav aria-label="Main" class="hidden items-baseline gap-8 md:flex">
            <a href="/" class="text-sm font-medium text-zinc-600 hover:text-zinc-900">
                Home
            </a>
            <a href="/services" class="text-sm font-medium text-zinc-600 hover:text-zinc-900">
                Services
            </a>
            <a href="/contact" class="text-sm font-medium text-zinc-600 hover:text-zinc-900">
                Contact
            </a>
        </nav>

        <a href="/contact" class="bg-[var(--primary-700)] px-4 py-2 text-sm font-semibold text-white hover:bg-[var(--primary-800)]">
            Request a consultation
        </a>
    </div>
</header>
