@extends('sites.layouts.preview')

@section('content')
    @include('sites.partials.default-nav')

    <main class="bg-neutral-100 py-10 text-neutral-900">
        <div class="mx-auto max-w-2xl px-4 sm:px-6">
            @yield('page')
        </div>
    </main>
@endsection
