@extends('sites.layouts.preview')

@section('content')
    <div class="min-h-screen bg-white text-neutral-900">
        <header class="bg-neutral-900 px-4 py-8 text-white">
            <div class="mx-auto max-w-2xl">
                <p class="text-xs font-medium uppercase tracking-wide text-neutral-400">
                    Template: alternate
                </p>
                <div class="mt-4 flex flex-col items-start gap-4 sm:flex-row sm:items-center">
                    @if ($site->logo)
                        <img
                            src="{{ \Illuminate\Support\Str::startsWith($site->logo, ['http://', 'https://']) ? $site->logo : asset($site->logo) }}"
                            alt=""
                            class="max-h-14 max-w-[180px] object-contain brightness-0 invert"
                        />
                    @endif
                    <h1 class="text-3xl font-bold tracking-tight">{{ $site->company_name }}</h1>
                </div>
            </div>
        </header>
        <main class="mx-auto max-w-2xl px-4 py-10">
            <ul class="space-y-3 text-sm">
                <li class="flex border-b border-neutral-200 py-2">
                    <span class="w-28 shrink-0 font-medium text-neutral-500">Phone</span>
                    <span>{{ $site->phone ?? '—' }}</span>
                </li>
                <li class="flex border-b border-neutral-200 py-2">
                    <span class="w-28 shrink-0 font-medium text-neutral-500">Email</span>
                    <span>{{ $site->email ?? '—' }}</span>
                </li>
                <li class="flex border-b border-neutral-200 py-2">
                    <span class="w-28 shrink-0 font-medium text-neutral-500">Slug</span>
                    <span>{{ $site->slug }}</span>
                </li>
            </ul>
        </main>
    </div>
@endsection
