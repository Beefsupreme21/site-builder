@extends('sites.layout')

@section('content')
    @forelse ($page->blockPages as $block)
        {!! $block->content !!}
    @empty
        <section class="mx-auto max-w-3xl px-6 py-20 text-center">
            <h1 class="text-4xl font-semibold tracking-tight text-neutral-900">
                {{ $page->title }}
            </h1>
            <p class="mt-4 text-sm text-neutral-500">
                No blocks on this page yet.
            </p>
        </section>
    @endforelse
@endsection
