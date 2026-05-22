@extends('sites.templates.default.layout')

@section('page')
    <article class="rounded-lg border border-neutral-200 bg-white p-8 shadow-sm">
        <h1 class="text-2xl font-semibold">{{ $sitePage->title }}</h1>
        @if ($sitePage->content)
            <p class="mt-4 text-sm leading-relaxed text-neutral-600">
                {{ $sitePage->content }}
            </p>
        @endif

        @if ($sitePage->isContactPage() && ($site->phone || $site->email))
            <dl class="mt-8 space-y-2 border-t border-neutral-100 pt-6 text-sm text-neutral-600">
                @if ($site->phone)
                    <div>
                        <dt class="font-medium text-neutral-500">Phone</dt>
                        <dd>
                            <a href="tel:{{ preg_replace('/[^\d+]/', '', $site->phone) }}" class="text-neutral-900 hover:underline">
                                {{ $site->phone }}
                            </a>
                        </dd>
                    </div>
                @endif
                @if ($site->email)
                    <div>
                        <dt class="font-medium text-neutral-500">Email</dt>
                        <dd>
                            <a href="mailto:{{ $site->email }}" class="text-neutral-900 hover:underline">
                                {{ $site->email }}
                            </a>
                        </dd>
                    </div>
                @endif
            </dl>
        @endif
    </article>
@endsection
