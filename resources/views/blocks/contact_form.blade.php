<section class="bg-white px-6 py-16 sm:py-24">
    <div class="mx-auto max-w-xl">
        <div class="text-center">
            <h2 class="text-3xl font-semibold tracking-tight text-neutral-900 sm:text-4xl">
                Get in touch
            </h2>
            <p class="mt-3 text-sm text-neutral-600">
                We'd love to hear from you. Drop us a line and we'll get back as soon as we can.
            </p>
        </div>
        <form class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2" onsubmit="event.preventDefault()">
            <div>
                <label class="block text-sm font-medium text-neutral-800">First name</label>
                <input type="text" class="mt-2 w-full rounded-md border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-neutral-500 focus:outline-none focus:ring-1 focus:ring-neutral-400" />
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-800">Last name</label>
                <input type="text" class="mt-2 w-full rounded-md border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-neutral-500 focus:outline-none focus:ring-1 focus:ring-neutral-400" />
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-neutral-800">Email</label>
                <input type="email" class="mt-2 w-full rounded-md border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-neutral-500 focus:outline-none focus:ring-1 focus:ring-neutral-400" />
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-neutral-800">Message</label>
                <textarea rows="4" class="mt-2 w-full rounded-md border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-neutral-500 focus:outline-none focus:ring-1 focus:ring-neutral-400"></textarea>
            </div>
            <div class="sm:col-span-2">
                <button type="submit" class="w-full rounded-md bg-[var(--primary)] px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-[var(--primary-dark)]">
                    Send message
                </button>
            </div>
        </form>
    </div>
</section>
