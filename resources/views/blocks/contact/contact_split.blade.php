<section class="bg-white">
    <div class="mx-auto max-w-6xl px-6 py-16 sm:py-24">
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-2 lg:gap-16">
            <div>
                <h1 class="text-balance text-4xl font-semibold tracking-tight text-slate-900 sm:text-5xl">
                    Request an appointment
                </h1>
                <p class="mt-6 max-w-xl text-lg/8 text-slate-600">
                    Send this through and we will call you back within one working
                    day with two or three times to choose from. If it is urgent,
                    please phone instead.
                </p>

                <form class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="contact-first-name" class="block text-sm font-medium text-slate-900">
                            First name
                        </label>
                        <input
                            type="text"
                            id="contact-first-name"
                            name="first_name"
                            autocomplete="given-name"
                            class="mt-2 block w-full rounded-xl border border-slate-500 bg-white px-4 py-3 text-base text-slate-900 shadow-[inset_0_2px_2px_rgba(0,0,0,0.06)] focus:border-[var(--primary-600)] focus:outline-none focus:ring-2 focus:ring-[var(--primary-600)]/30"
                        />
                    </div>

                    <div>
                        <label for="contact-last-name" class="block text-sm font-medium text-slate-900">
                            Last name
                        </label>
                        <input
                            type="text"
                            id="contact-last-name"
                            name="last_name"
                            autocomplete="family-name"
                            class="mt-2 block w-full rounded-xl border border-slate-500 bg-white px-4 py-3 text-base text-slate-900 shadow-[inset_0_2px_2px_rgba(0,0,0,0.06)] focus:border-[var(--primary-600)] focus:outline-none focus:ring-2 focus:ring-[var(--primary-600)]/30"
                        />
                    </div>

                    <div class="sm:col-span-2">
                        <label for="contact-phone" class="block text-sm font-medium text-slate-900">
                            Phone
                        </label>
                        <input
                            type="tel"
                            id="contact-phone"
                            name="phone"
                            autocomplete="tel"
                            class="mt-2 block w-full rounded-xl border border-slate-500 bg-white px-4 py-3 text-base text-slate-900 shadow-[inset_0_2px_2px_rgba(0,0,0,0.06)] focus:border-[var(--primary-600)] focus:outline-none focus:ring-2 focus:ring-[var(--primary-600)]/30"
                        />
                        <p class="mt-2 text-sm text-slate-500">
                            We call rather than email, so this one matters most.
                        </p>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="contact-reason" class="block text-sm font-medium text-slate-900">
                            What do you need?
                        </label>
                        <textarea
                            id="contact-reason"
                            name="reason"
                            rows="4"
                            class="mt-2 block w-full rounded-xl border border-slate-500 bg-white px-4 py-3 text-base text-slate-900 shadow-[inset_0_2px_2px_rgba(0,0,0,0.06)] focus:border-[var(--primary-600)] focus:outline-none focus:ring-2 focus:ring-[var(--primary-600)]/30"
                        ></textarea>
                    </div>

                    <div class="sm:col-span-2">
                        <button type="submit" class="w-full rounded-xl bg-[var(--primary-600)] px-5 py-3 text-sm font-semibold text-white shadow-md hover:bg-[var(--primary-700)] sm:w-auto">
                            Send request
                        </button>
                    </div>
                </form>
            </div>

            <div class="flex flex-col gap-8">
                <div class="rounded-xl bg-slate-50 p-8">
                    <h2 class="text-base font-semibold text-slate-900">
                        Opening hours
                    </h2>
                    <dl class="mt-6 divide-y divide-slate-200">
                        <div class="flex items-baseline justify-between gap-6 py-3">
                            <dt class="text-base text-slate-600">Monday, Tuesday, Thursday</dt>
                            <dd class="text-base font-medium text-slate-900">8am &ndash; 5pm</dd>
                        </div>
                        <div class="flex items-baseline justify-between gap-6 py-3">
                            <dt class="text-base text-slate-600">Wednesday</dt>
                            <dd class="text-base font-medium text-slate-900">8am &ndash; 8pm</dd>
                        </div>
                        <div class="flex items-baseline justify-between gap-6 py-3">
                            <dt class="text-base text-slate-600">Friday</dt>
                            <dd class="text-base font-medium text-slate-900">8am &ndash; 1pm</dd>
                        </div>
                        <div class="flex items-baseline justify-between gap-6 py-3">
                            <dt class="text-base text-slate-600">Weekends</dt>
                            <dd class="text-base font-medium text-slate-500">Closed</dd>
                        </div>
                    </dl>
                </div>

                <div class="rounded-xl bg-slate-50 p-8">
                    <h2 class="text-base font-semibold text-slate-900">
                        Find us
                    </h2>
                    <address class="mt-6 flex flex-col gap-4 text-base/7 not-italic text-slate-600">
                        <span>
                            118 Fernwood Road, Suite 2<br />
                            Minneapolis, MN 55408
                        </span>
                        <a href="tel:+16125550119" class="font-semibold text-slate-900 hover:underline">
                            (612) 555-0119
                        </a>
                        <a href="mailto:front.desk@fernwooddental.example" class="font-medium text-[var(--primary-700)] hover:underline">
                            front.desk@fernwooddental.example
                        </a>
                    </address>
                    <p class="mt-6 text-sm text-slate-500">
                        Free patient parking is behind the building, off Weller
                        Lane. The entrance has a ramp and a lift to the second
                        floor.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
