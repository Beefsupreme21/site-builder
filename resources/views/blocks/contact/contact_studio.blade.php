<section class="bg-white">
    <div class="mx-auto max-w-6xl px-6 py-16 sm:py-24">
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-5 lg:gap-16">
            <div class="lg:col-span-3">
                <h1 class="text-balance font-serif text-4xl text-zinc-900 sm:text-5xl">
                    Request a consultation
                </h1>
                <p class="mt-6 max-w-xl text-lg/8 text-zinc-600">
                    Tell us roughly where you are and what you are hoping for. We
                    reply within two working days, and we will say so plainly if
                    your project is not a good fit for us.
                </p>

                <form class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="studio-name" class="block text-sm font-medium text-zinc-900">
                            Your name
                        </label>
                        <input
                            type="text"
                            id="studio-name"
                            name="name"
                            autocomplete="name"
                            class="mt-2 block w-full border border-zinc-500 bg-white px-4 py-3 text-base text-zinc-900 shadow-[inset_0_2px_2px_rgba(0,0,0,0.06)] focus:border-[var(--primary-700)] focus:outline-none focus:ring-2 focus:ring-[var(--primary-700)]/30"
                        />
                    </div>

                    <div>
                        <label for="studio-email" class="block text-sm font-medium text-zinc-900">
                            Email
                        </label>
                        <input
                            type="email"
                            id="studio-email"
                            name="email"
                            autocomplete="email"
                            class="mt-2 block w-full border border-zinc-500 bg-white px-4 py-3 text-base text-zinc-900 shadow-[inset_0_2px_2px_rgba(0,0,0,0.06)] focus:border-[var(--primary-700)] focus:outline-none focus:ring-2 focus:ring-[var(--primary-700)]/30"
                        />
                    </div>

                    <div class="sm:col-span-2">
                        <label for="studio-town" class="block text-sm font-medium text-zinc-900">
                            Where is the garden?
                        </label>
                        <input
                            type="text"
                            id="studio-town"
                            name="town"
                            class="mt-2 block w-full border border-zinc-500 bg-white px-4 py-3 text-base text-zinc-900 shadow-[inset_0_2px_2px_rgba(0,0,0,0.06)] focus:border-[var(--primary-700)] focus:outline-none focus:ring-2 focus:ring-[var(--primary-700)]/30"
                        />
                        <p class="mt-2 text-sm text-zinc-500">
                            We work within about ninety minutes of Portland.
                        </p>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="studio-brief" class="block text-sm font-medium text-zinc-900">
                            What are you hoping for?
                        </label>
                        <textarea
                            id="studio-brief"
                            name="brief"
                            rows="5"
                            class="mt-2 block w-full border border-zinc-500 bg-white px-4 py-3 text-base text-zinc-900 shadow-[inset_0_2px_2px_rgba(0,0,0,0.06)] focus:border-[var(--primary-700)] focus:outline-none focus:ring-2 focus:ring-[var(--primary-700)]/30"
                        ></textarea>
                    </div>

                    <div class="sm:col-span-2">
                        <button type="submit" class="w-full bg-[var(--primary-700)] px-5 py-3 text-sm font-semibold text-white hover:bg-[var(--primary-800)] sm:w-auto">
                            Send enquiry
                        </button>
                    </div>
                </form>
            </div>

            <div class="lg:col-span-2">
                <div class="border-t-2 border-[var(--primary-700)] bg-zinc-50 p-8">
                    <h2 class="font-serif text-2xl text-zinc-900">
                        The studio
                    </h2>
                    <address class="mt-6 flex flex-col gap-4 text-base/7 not-italic text-zinc-600">
                        <span>
                            9 Cobble Wharf<br />
                            Portland, ME 04101
                        </span>
                        <a href="tel:+12075550132" class="font-semibold text-zinc-900 hover:underline">
                            (207) 555-0132
                        </a>
                        <a href="mailto:studio@willow.example" class="font-medium text-[var(--primary-700)] hover:underline">
                            studio@willow.example
                        </a>
                    </address>

                    <dl class="mt-8 divide-y divide-zinc-200 border-t border-zinc-200">
                        <div class="flex items-baseline justify-between gap-6 py-3">
                            <dt class="text-sm text-zinc-600">Studio hours</dt>
                            <dd class="text-sm font-medium text-zinc-900">Mon &ndash; Thu, 9am &ndash; 5pm</dd>
                        </div>
                        <div class="flex items-baseline justify-between gap-6 py-3">
                            <dt class="text-sm text-zinc-600">Site visits</dt>
                            <dd class="text-sm font-medium text-zinc-900">Fridays</dd>
                        </div>
                        <div class="flex items-baseline justify-between gap-6 py-3">
                            <dt class="text-sm text-zinc-600">Booking out to</dt>
                            <dd class="text-sm font-medium text-[var(--primary-700)]">Spring next year</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</section>
