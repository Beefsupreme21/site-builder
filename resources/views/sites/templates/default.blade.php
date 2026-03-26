@extends('sites.layouts.preview')

@section('content')
    <div class="bg-neutral-100 py-10 text-neutral-900">
        <div class="mx-auto max-w-2xl px-4">
            <p class="text-xs font-medium uppercase tracking-wide text-neutral-500">
                Template: default
            </p>
            <div class="mt-4 rounded-lg border border-neutral-200 bg-white p-8 shadow-sm">
                <div class="flex flex-col gap-6 sm:flex-row sm:items-start">
                    @if ($site->logo)
                        <div class="shrink-0">
                            <img
                                src="{{ \Illuminate\Support\Str::startsWith($site->logo, ['http://', 'https://']) ? $site->logo : asset($site->logo) }}"
                                alt=""
                                class="max-h-20 max-w-[200px] object-contain"
                            />
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <h1 class="text-2xl font-semibold">{{ $site->company_name }}</h1>
                        <dl class="mt-6 space-y-2 text-sm text-neutral-600">
                            <div>
                                <dt class="font-medium text-neutral-500">Phone</dt>
                                <dd>{{ $site->phone ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-neutral-500">Email</dt>
                                <dd>{{ $site->email ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-neutral-500">Slug</dt>
                                <dd>{{ $site->slug }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
