@extends('sites.templates.alternate.layout')

@php
    $heroImage = 'https://images.unsplash.com/photo-1515703407324-5f753afd8be8?auto=format&fit=crop&w=2400&q=80';
@endphp

@section('content')
    @if ($sitePage->isHomePage())
        <div
            class="relative isolate px-6 py-24 sm:py-32 lg:px-8 lg:py-36"
            style="background-image: url('{{ $heroImage }}'); background-size: cover; background-position: center; background-repeat: no-repeat;"
        >
            <div class="absolute inset-0 bg-black/50"></div>
            <div class="relative mx-auto max-w-2xl text-center">
                <h1 class="text-balance text-5xl font-semibold tracking-tight text-white sm:text-7xl">{{ $sitePage->title }}</h1>
                <div class="mx-auto mt-4 h-1 w-24 bg-[#1b4896]"></div>
                @if ($sitePage->content)
                    <p class="mt-8 text-pretty text-lg font-medium text-gray-100 sm:text-xl/8">
                        {{ $sitePage->content }}
                    </p>
                @endif
            </div>
        </div>
    @else
        <div class="bg-white px-6 py-16 sm:py-24 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <h1 class="text-pretty text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl">{{ $sitePage->title }}</h1>
                <div class="mx-auto mt-4 h-1 w-24 bg-[#1b4896]"></div>
                @if ($sitePage->content)
                    <p class="mx-auto mt-8 max-w-2xl text-pretty text-lg/8 text-gray-600">
                        {{ $sitePage->content }}
                    </p>
                @endif
                @if ($sitePage->isContactPage())
                    @if ($site->phone)
                        <p class="mt-6 text-base text-gray-600">
                            <a href="tel:{{ preg_replace('/[^\d+]/', '', $site->phone) }}" class="font-medium text-[#1b4896] hover:underline">{{ $site->phone }}</a>
                        </p>
                    @endif
                    @if ($site->email)
                        <p class="mt-1 text-base text-gray-600">
                            <a href="mailto:{{ $site->email }}" class="font-medium text-[#1b4896] hover:underline">{{ $site->email }}</a>
                        </p>
                    @endif
                @endif
            </div>
        </div>
    @endif

    @if ($sitePage->isContactPage())
        @include('sites.partials.alternate-contact-form')
    @endif
@endsection
