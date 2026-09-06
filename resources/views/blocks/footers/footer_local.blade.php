<footer class="border-t border-stone-200 bg-white">
    <div class="mx-auto max-w-6xl px-6 py-12">
        <div class="flex flex-col gap-10 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-[var(--primary-700)] text-xs font-semibold tracking-wider text-white">
                        RC
                    </span>
                    <span class="text-base font-semibold tracking-tight text-stone-900">
                        Ridgeline Coffee
                    </span>
                </div>
                <p class="mt-4 max-w-xs text-sm/6 text-stone-600">
                    412 Ridgeline Ave, Portland OR 97210. Two blocks up from the
                    tram stop.
                </p>
            </div>

            <nav aria-label="Footer" class="flex flex-col gap-3">
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

            <div class="flex flex-col gap-2">
                <a href="tel:+15035550148" class="text-sm font-semibold text-stone-900 hover:underline">
                    (503) 555-0148
                </a>
                <a href="mailto:hello@ridgelinecoffee.example" class="text-sm font-medium text-stone-600 hover:text-stone-900">
                    hello@ridgelinecoffee.example
                </a>
            </div>
        </div>

        <p class="mt-10 border-t border-stone-200 pt-8 text-sm text-stone-500">
            &copy; {{ date('Y') }} Ridgeline Coffee. Roasted on Ridgeline Ave.
        </p>
    </div>
</footer>
