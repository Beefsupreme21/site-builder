<footer class="border-t border-slate-200 bg-white">
    <div class="mx-auto max-w-6xl px-6 py-16">
        <div class="grid grid-cols-1 gap-12 sm:grid-cols-2 lg:grid-cols-4">
            <div class="lg:col-span-2">
                <div class="flex items-center gap-3">
                    <span class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-teal-700">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" class="size-6 text-white">
                            <path
                                d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                    </span>
                    <span class="text-base font-semibold tracking-tight text-slate-900">
                        Fernwood Dental
                    </span>
                </div>
                <p class="mt-6 max-w-sm text-base/7 text-slate-600">
                    A two-chair family and cosmetic practice on Fernwood Road,
                    looking after south Minneapolis since 2005.
                </p>
            </div>

            <div>
                <h2 class="text-sm font-semibold text-slate-900">Practice</h2>
                <nav aria-label="Footer" class="mt-6 flex flex-col gap-3">
                    <a href="/" class="text-sm font-medium text-slate-600 hover:text-slate-900">Home</a>
                    <a href="/about" class="text-sm font-medium text-slate-600 hover:text-slate-900">About</a>
                    <a href="/contact" class="text-sm font-medium text-slate-600 hover:text-slate-900">Contact</a>
                </nav>
            </div>

            <div>
                <h2 class="text-sm font-semibold text-slate-900">Get in touch</h2>
                <address class="mt-6 flex flex-col gap-3 text-sm not-italic">
                    <span class="text-slate-600">
                        118 Fernwood Road, Suite 2<br />
                        Minneapolis, MN 55408
                    </span>
                    <a href="tel:+16125550119" class="font-semibold text-slate-900 hover:underline">
                        (612) 555-0119
                    </a>
                    <a href="mailto:front.desk@fernwooddental.example" class="font-medium text-slate-600 hover:text-slate-900">
                        front.desk@fernwooddental.example
                    </a>
                </address>
            </div>
        </div>

        <div class="mt-12 flex flex-col gap-2 border-t border-slate-200 pt-8 sm:flex-row sm:items-baseline sm:justify-between">
            <p class="text-sm text-slate-500">
                &copy; {{ date('Y') }} Fernwood Dental, PLLC.
            </p>
            <p class="text-sm text-slate-500">
                Emergencies before noon are seen the same day.
            </p>
        </div>
    </div>
</footer>
