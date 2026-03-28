@php
    $logoUrl = $site->logo
        ? (\Illuminate\Support\Str::startsWith($site->logo, ['http://', 'https://']) ? $site->logo : asset($site->logo))
        : 'https://placehold.co/240x96/png?text='.urlencode($site->company_name);
    $heroImage = 'https://images.unsplash.com/photo-1515703407324-5f753afd8be8?auto=format&fit=crop&w=2400&q=80';
    $contactThankYouMessage = 'Thank you — we received your message and will be in touch soon.';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $site->company_name }}</title>
        @vite(['resources/css/app.css'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>
        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>
    </head>
    <body x-data="{ mobileOpen: false }">
        <div class="bg-white">
            {{-- Header --}}
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
                        <a href="#" class="-m-1.5 p-1.5">
                            <span class="sr-only">{{ $site->company_name }}</span>
                            <img src="{{ $logoUrl }}" alt="" class="h-20 w-auto object-contain lg:h-24" />
                        </a>
                        <div class="flex gap-x-12">
                            <a href="#" class="text-base font-semibold text-gray-800">Home</a>
                            <a href="#our-team" class="text-base font-semibold text-gray-800">About Us</a>
                            <a href="#testimonials" class="text-base font-semibold text-gray-800">Testimonials</a>
                            <a href="#contact" class="text-base font-semibold text-gray-800">Contact Us</a>
                        </div>
                    </div>
                    <div class="flex lg:hidden">
                        <a href="#" class="-m-1.5 p-1.5">
                            <span class="sr-only">{{ $site->company_name }}</span>
                            <img src="{{ $logoUrl }}" alt="" class="h-16 w-auto object-contain sm:h-20" />
                        </a>
                    </div>
                </nav>

                {{-- Mobile menu --}}
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
                            <a href="#" class="-m-1.5 p-1.5">
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
                        <div class="mt-6 flow-root">
                            <div class="-my-6 divide-y divide-gray-500/10">
                                <div class="space-y-2 py-6">
                                    <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50" @click="mobileOpen = false">Home</a>
                                    <a href="#our-team" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50" @click="mobileOpen = false">About Us</a>
                                    <a href="#testimonials" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50" @click="mobileOpen = false">Testimonials</a>
                                    <a href="#contact" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50" @click="mobileOpen = false">Contact Us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Hero --}}
            <div
                class="relative isolate px-6 py-24 sm:py-32 lg:px-8 lg:py-36"
                style="background-image: url('{{ $heroImage }}'); background-size: cover; background-position: center; background-repeat: no-repeat;"
            >
                <div class="absolute inset-0 bg-black/50"></div>
                <div class="relative mx-auto max-w-2xl text-center">
                    <h1 class="text-balance text-5xl font-semibold tracking-tight text-white sm:text-7xl">{{ $site->company_name }}</h1>
                    <div class="mx-auto mt-4 h-1 w-24 bg-[#1b4896]"></div>
                    <p class="mt-8 text-pretty text-lg font-medium text-gray-100 sm:text-xl/8">
                        Building trusted relationships and delivering excellence for every client we serve.
                        @if ($site->phone || $site->email)
                            Get in touch below—we would love to hear from you.
                        @endif
                    </p>
                    <div class="mt-10 flex justify-center">
                        <a href="#contact" class="rounded-md bg-[#1b4896] px-6 py-3.5 text-base font-semibold text-white shadow-xs hover:bg-[#153d7a] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#1b4896]">Contact Us</a>
                    </div>
                </div>
            </div>

            {{-- Our team (demo content) --}}
            <div id="our-team" class="bg-[#2f2e2e] py-24 sm:py-32">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="text-center">
                        <h2 class="text-pretty text-4xl font-semibold tracking-tight text-white sm:text-5xl">Our team</h2>
                        <div class="mx-auto mt-4 h-1 w-24 bg-[#1b4896]"></div>
                    </div>
                    <ul role="list" class="mx-auto mt-20 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 sm:grid-cols-2 lg:mx-0 lg:max-w-none lg:grid-cols-3">
                        <li>
                            <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?w=1024&q=80" alt="" class="aspect-[3/2] w-full rounded-2xl object-cover outline outline-1 -outline-offset-1 outline-white/10" />
                            <h3 class="mt-6 text-lg/8 font-semibold tracking-tight text-white">Alex Morgan</h3>
                            <p class="text-base/7 text-gray-400">Managing Director</p>
                        </li>
                        <li>
                            <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=1024&q=80" alt="" class="aspect-[3/2] w-full rounded-2xl object-cover outline outline-1 -outline-offset-1 outline-white/10" />
                            <h3 class="mt-6 text-lg/8 font-semibold tracking-tight text-white">Jordan Lee</h3>
                            <p class="text-base/7 text-gray-400">Client Success</p>
                        </li>
                        <li>
                            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=1024&q=80" alt="" class="aspect-[3/2] w-full rounded-2xl object-cover outline outline-1 -outline-offset-1 outline-white/10" />
                            <h3 class="mt-6 text-lg/8 font-semibold tracking-tight text-white">Sam Rivera</h3>
                            <p class="text-base/7 text-gray-400">Operations</p>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Testimonials --}}
            <div id="testimonials" class="relative isolate bg-white py-24 sm:py-32">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="text-center">
                        <h2 class="text-pretty text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl">Testimonials</h2>
                        <div class="mx-auto mt-4 h-1 w-24 bg-[#1b4896]"></div>
                    </div>
                    <div class="mx-auto mt-16 flow-root max-w-2xl sm:mt-20 lg:mx-0 lg:max-w-none">
                        <div class="-mt-8 sm:-mx-4 sm:columns-2 sm:text-[0] lg:columns-3">
                            @foreach ([
                                ['q' => 'Outstanding professionalism and attention to detail. We could not be happier with the partnership.', 'a' => 'Leslie Alexander'],
                                ['q' => 'Strategic, responsive, and genuinely invested in our success.', 'a' => 'Michael Foster'],
                                ['q' => 'A trusted team that delivers results without the noise.', 'a' => 'Lindsay Walton'],
                                ['q' => 'Clear communication and creative thinking—exactly what we needed.', 'a' => 'Courtney Henry'],
                                ['q' => 'Reliable partners who care about outcomes, not just deliverables.', 'a' => 'Whitney Francis'],
                                ['q' => 'We recommend them without hesitation.', 'a' => 'Leonard Krasner'],
                            ] as $t)
                                <div class="pt-8 sm:inline-block sm:w-full sm:px-4">
                                    <figure class="rounded-2xl bg-gray-50 p-8 text-sm/6">
                                        <blockquote class="text-gray-900">
                                            <p>“{{ $t['q'] }}”</p>
                                        </blockquote>
                                        <figcaption class="mt-6 text-sm text-gray-600">— {{ $t['a'] }}</figcaption>
                                    </figure>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Partners --}}
            <div class="bg-[#2f2e2e] py-24 sm:py-32">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="text-center">
                        <h2 class="text-pretty text-4xl font-semibold tracking-tight text-white sm:text-5xl">Our partners</h2>
                        <div class="mx-auto mt-4 h-1 w-24 bg-[#1b4896]"></div>
                    </div>
                    <div class="mx-auto mt-20 grid max-w-lg grid-cols-4 items-center gap-x-8 gap-y-10 sm:max-w-xl sm:grid-cols-6 sm:gap-x-10 lg:mx-0 lg:max-w-none lg:grid-cols-5">
                        <img width="158" height="48" src="https://tailwindcss.com/plus-assets/img/logos/158x48/transistor-logo-white.svg" alt="Transistor" class="col-span-2 max-h-12 w-full object-contain lg:col-span-1" />
                        <img width="158" height="48" src="https://tailwindcss.com/plus-assets/img/logos/158x48/reform-logo-white.svg" alt="Reform" class="col-span-2 max-h-12 w-full object-contain lg:col-span-1" />
                        <img width="158" height="48" src="https://tailwindcss.com/plus-assets/img/logos/158x48/tuple-logo-white.svg" alt="Tuple" class="col-span-2 max-h-12 w-full object-contain lg:col-span-1" />
                        <img width="158" height="48" src="https://tailwindcss.com/plus-assets/img/logos/158x48/savvycal-logo-white.svg" alt="SavvyCal" class="col-span-2 max-h-12 w-full object-contain sm:col-start-2 lg:col-span-1" />
                        <img width="158" height="48" src="https://tailwindcss.com/plus-assets/img/logos/158x48/statamic-logo-white.svg" alt="Statamic" class="col-span-2 col-start-2 max-h-12 w-full object-contain sm:col-start-auto lg:col-span-1" />
                    </div>
                </div>
            </div>

            {{-- Contact (in-page submit via fetch when Alpine runs; otherwise normal POST + redirect) --}}
            <div
                id="contact"
                class="isolate bg-white px-6 py-24 sm:py-32 lg:px-8"
                x-data="{
                    submitting: false,
                    success: false,
                    thankYou: @js($contactThankYouMessage),
                    errorList: [],
                    defaultEmail: @js($site->email),
                    defaultPhone: @js($site->phone),
                    async submitContact(e) {
                        e.preventDefault();
                        this.submitting = true;
                        this.success = false;
                        this.errorList = [];
                        const form = e.target;
                        const token = document.querySelector('meta[name=csrf-token]')?.getAttribute('content') ?? '';
                        try {
                            const res = await fetch(form.action, {
                                method: 'POST',
                                body: new FormData(form),
                                headers: {
                                    Accept: 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': token,
                                },
                                credentials: 'same-origin',
                            });
                            const data = await res.json().catch(() => ({}));
                            if (res.ok) {
                                this.success = true;
                                form.reset();
                                const emailEl = form.querySelector('[name=email]');
                                const phoneEl = form.querySelector('[name=phone]');
                                if (emailEl && this.defaultEmail) {
                                    emailEl.value = this.defaultEmail;
                                }
                                if (phoneEl && this.defaultPhone) {
                                    phoneEl.value = this.defaultPhone;
                                }
                                const hp = form.querySelector('[name=fax_extension]');
                                if (hp) {
                                    hp.value = '';
                                }
                            } else if (res.status === 422 && data.errors) {
                                this.errorList = Object.values(data.errors).flat().map(String);
                            } else {
                                this.errorList = [data.message || 'Something went wrong. Please try again.'];
                            }
                        } catch {
                            this.errorList = ['Something went wrong. Please try again.'];
                        } finally {
                            this.submitting = false;
                        }
                    },
                }"
            >
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="text-pretty text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl">Contact Us</h2>
                    <div class="mx-auto mt-4 h-1 w-24 bg-[#1b4896]"></div>
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
                </div>
                @if (session('lead_submitted'))
                    <div class="mx-auto mt-8 max-w-xl rounded-md border border-green-200 bg-green-50 px-4 py-3 text-center text-sm text-green-900">
                        {{ $contactThankYouMessage }}
                    </div>
                @endif
                <div
                    x-show="success"
                    x-cloak
                    class="mx-auto mt-8 max-w-xl rounded-md border border-green-200 bg-green-50 px-4 py-3 text-center text-sm text-green-900"
                    x-text="thankYou"
                ></div>
                <div
                    x-show="errorList.length > 0"
                    x-cloak
                    class="mx-auto mt-8 max-w-xl rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900"
                    role="alert"
                >
                    <p class="font-semibold">Please fix the following:</p>
                    <ul class="mt-2 list-inside list-disc">
                        <template x-for="(err, idx) in errorList" :key="idx">
                            <li x-text="err"></li>
                        </template>
                    </ul>
                </div>
                @if ($errors->any())
                    <div class="mx-auto mt-8 max-w-xl rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900" role="alert">
                        <p class="font-semibold">Please fix the following:</p>
                        <ul class="mt-2 list-inside list-disc">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form
                    action="{{ route('sites.contact.store', $site) }}"
                    method="POST"
                    class="relative mx-auto mt-16 max-w-xl sm:mt-20"
                    @submit="submitContact($event)"
                >
                    @csrf
                    {{-- Honeypot: leave as a normal text field (not type=hidden) so simple bots fill it and fail validation. --}}
                    <div class="pointer-events-none absolute top-0 left-0 -z-10 h-px w-px overflow-hidden opacity-0" aria-hidden="true">
                        <label for="fax_extension">Fax extension</label>
                        <input
                            id="fax_extension"
                            type="text"
                            name="fax_extension"
                            tabindex="-1"
                            autocomplete="off"
                            value=""
                        />
                    </div>
                    <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
                        <div>
                            <label for="first_name" class="block text-sm/6 font-semibold text-gray-900">First name</label>
                            <div class="mt-2.5">
                                <input id="first_name" type="text" name="first_name" autocomplete="given-name" value="{{ old('first_name') }}" class="block w-full rounded-md bg-white px-3.5 py-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-[#1b4896]" />
                            </div>
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm/6 font-semibold text-gray-900">Last name</label>
                            <div class="mt-2.5">
                                <input id="last_name" type="text" name="last_name" autocomplete="family-name" value="{{ old('last_name') }}" class="block w-full rounded-md bg-white px-3.5 py-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-[#1b4896]" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="email" class="block text-sm/6 font-semibold text-gray-900">Email</label>
                            <div class="mt-2.5">
                                <input id="email" type="email" name="email" autocomplete="email" value="{{ old('email', $site->email) }}" class="block w-full rounded-md bg-white px-3.5 py-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-[#1b4896]" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="phone" class="block text-sm/6 font-semibold text-gray-900">Phone number</label>
                            <div class="mt-2.5">
                                <input id="phone" type="text" name="phone" placeholder="123-456-7890" value="{{ old('phone', $site->phone) }}" class="block w-full rounded-md bg-white px-3.5 py-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-[#1b4896]" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="message" class="block text-sm/6 font-semibold text-gray-900">Message</label>
                            <div class="mt-2.5">
                                <textarea id="message" name="message" rows="4" class="block w-full rounded-md bg-white px-3.5 py-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-[#1b4896]">{{ old('message') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mt-10">
                        <button
                            type="submit"
                            :disabled="submitting"
                            class="block w-full rounded-md bg-[#1b4896] px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-xs hover:bg-[#153d7a] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#1b4896] disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            <span x-show="!submitting">Let's talk</span>
                            <span x-show="submitting" x-cloak>Sending...</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Footer --}}
            <footer class="bg-[#2f2e2e]">
                <div class="mx-auto max-w-7xl overflow-hidden px-6 py-20 sm:py-24 lg:px-8">
                    <nav aria-label="Footer" class="-mb-6 flex flex-wrap justify-center gap-x-12 gap-y-3 text-sm/6">
                        <a href="#" class="text-gray-400 hover:text-white">Home</a>
                        <a href="#our-team" class="text-gray-400 hover:text-white">About Us</a>
                        <a href="#testimonials" class="text-gray-400 hover:text-white">Testimonials</a>
                        <a href="#contact" class="text-gray-400 hover:text-white">Contact Us</a>
                    </nav>
                    <div class="mt-16 flex justify-center gap-x-10">
                        <a href="#" class="text-gray-400 hover:text-white" aria-label="Facebook">
                            <svg viewBox="0 0 24 24" fill="currentColor" class="size-6" aria-hidden="true">
                                <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white" aria-label="Instagram">
                            <svg viewBox="0 0 24 24" fill="currentColor" class="size-6" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white" aria-label="X">
                            <svg viewBox="0 0 24 24" fill="currentColor" class="size-6" aria-hidden="true">
                                <path d="M13.6823 10.6218L20.2391 3H18.6854L12.9921 9.61788L8.44486 3H3.2002L10.0765 13.0074L3.2002 21H4.75404L10.7663 14.0113L15.5685 21H20.8131L13.6819 10.6218H13.6823ZM11.5541 13.0956L10.8574 12.0991L5.31391 4.16971H7.70053L12.1742 10.5689L12.8709 11.5655L18.6861 19.8835H16.2995L11.5541 13.096V13.0956Z" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white" aria-label="YouTube">
                            <svg viewBox="0 0 24 24" fill="currentColor" class="size-6" aria-hidden="true">
                                <path fill-rule="evenodd" d="M19.812 5.418c.861.23 1.538.907 1.768 1.768C21.998 8.746 22 12 22 12s0 3.255-.418 4.814a2.504 2.504 0 01-1.768 1.768c-1.56.419-7.814.419-7.814.419s-6.255 0-7.814-.419a2.505 2.505 0 01-1.768-1.768C2 15.255 2 12 2 12s0-3.255.417-4.814a2.507 2.507 0 011.768-1.768C5.744 5 11.998 5 11.998 5s6.255 0 7.814.418ZM15.194 12 10 15V9l5.194 3Z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                    <p class="mt-10 text-center text-sm/6 text-gray-400">&copy; {{ now()->year }} {{ $site->company_name }}. All rights reserved.</p>
                </div>
            </footer>
        </div>
    </body>
</html>
