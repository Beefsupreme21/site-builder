@php
    $contactThankYouMessage = 'Thank you — we received your message and will be in touch soon.';
@endphp

<div
    class="isolate bg-white px-6 py-16 sm:py-20 lg:px-8"
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
    @if (session('lead_submitted'))
        <div class="mx-auto mb-8 max-w-xl rounded-md border border-green-200 bg-green-50 px-4 py-3 text-center text-sm text-green-900">
            {{ $contactThankYouMessage }}
        </div>
    @endif
    <div
        x-show="success"
        x-cloak
        class="mx-auto mb-8 max-w-xl rounded-md border border-green-200 bg-green-50 px-4 py-3 text-center text-sm text-green-900"
        x-text="thankYou"
    ></div>
    <div
        x-show="errorList.length > 0"
        x-cloak
        class="mx-auto mb-8 max-w-xl rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900"
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
        <div class="mx-auto mb-8 max-w-xl rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900" role="alert">
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
        class="relative mx-auto max-w-xl"
        @submit="submitContact($event)"
    >
        @csrf
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
