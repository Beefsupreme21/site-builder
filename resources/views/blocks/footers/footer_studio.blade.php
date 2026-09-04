<footer class="bg-zinc-900">
    <div class="mx-auto max-w-6xl px-6 py-16">
        <div class="grid grid-cols-1 gap-12 sm:grid-cols-3">
            <div>
                <p class="font-serif text-xl tracking-tight text-white">
                    Alder &amp; Vine
                </p>
                <p class="mt-4 max-w-xs text-base/7 text-zinc-300">
                    Landscape and garden design for coastal Maine, from a studio
                    on Cobble Wharf.
                </p>
            </div>

            <div>
                <h2 class="text-sm font-semibold text-white">Studio</h2>
                <nav aria-label="Footer" class="mt-6 flex flex-col gap-3">
                    <a href="/" class="text-sm font-medium text-zinc-300 hover:text-white">Home</a>
                    <a href="/services" class="text-sm font-medium text-zinc-300 hover:text-white">Services</a>
                    <a href="/contact" class="text-sm font-medium text-zinc-300 hover:text-white">Contact</a>
                </nav>
            </div>

            <div>
                <h2 class="text-sm font-semibold text-white">Get in touch</h2>
                <address class="mt-6 flex flex-col gap-3 text-sm not-italic">
                    <span class="text-zinc-300">
                        9 Cobble Wharf<br />
                        Portland, ME 04101
                    </span>
                    <a href="tel:+12075550132" class="font-semibold text-white hover:underline">
                        (207) 555-0132
                    </a>
                    <a href="mailto:studio@alderandvine.example" class="font-medium text-zinc-300 hover:text-white">
                        studio@alderandvine.example
                    </a>
                </address>
            </div>
        </div>

        <div class="mt-12 flex flex-col gap-2 border-t border-zinc-800 pt-8 sm:flex-row sm:items-baseline sm:justify-between">
            <p class="text-sm text-zinc-400">
                &copy; {{ date('Y') }} Alder &amp; Vine Landscape Design.
            </p>
            <p class="text-sm text-zinc-400">
                Currently booking spring next year.
            </p>
        </div>
    </div>
</footer>
