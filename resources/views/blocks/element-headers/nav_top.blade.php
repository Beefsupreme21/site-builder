<nav class="relative bg-white shadow">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
            <div class="flex">
                <div class="-ml-2 mr-2 flex items-center md:hidden">
                    <button type="button" command="--toggle" commandfor="mobile-menu" class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-600">
                        <span class="absolute -inset-0.5"></span>
                        <span class="sr-only">Open main menu</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6 [[aria-expanded='true']_&]:hidden">
                            <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6 [&:not([aria-expanded='true']_*)]:hidden">
                            <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>
                <div class="flex shrink-0 items-center">
                    <a href="/">
                        @php
                            $companyName = $site->company_name ?? 'Your Company';
                            $logoUrl = isset($site) ? \App\Support\SiteLogo::url($site->logo) : null;
                        @endphp
                        @if ($logoUrl)
                            <img src="{{ $logoUrl }}" alt="{{ $companyName }}" class="h-8 w-auto" />
                        @else
                            <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600" alt="{{ $companyName }}" class="h-8 w-auto" />
                        @endif
                    </a>
                </div>
                <div class="hidden md:ml-6 md:flex md:space-x-8">
                    <a href="/" class="inline-flex items-center border-b-2 border-indigo-600 px-1 pt-1 text-sm font-medium text-gray-900">Home</a>
                    <a href="/about" class="inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">About</a>
                    <a href="/contact" class="inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">Contact</a>
                </div>
            </div>
        </div>
    </div>

    <el-disclosure id="mobile-menu" hidden class="md:hidden [&:not([hidden])]:block">
        <div class="space-y-1 pb-3 pt-2">
            <a href="/" class="block border-l-4 border-indigo-600 bg-indigo-50 py-2 pl-3 pr-4 text-base font-medium text-indigo-700 sm:pl-5 sm:pr-6">Home</a>
            <a href="/about" class="block border-l-4 border-transparent py-2 pl-3 pr-4 text-base font-medium text-gray-500 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-800 sm:pl-5 sm:pr-6">About</a>
            <a href="/contact" class="block border-l-4 border-transparent py-2 pl-3 pr-4 text-base font-medium text-gray-500 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-800 sm:pl-5 sm:pr-6">Contact</a>
        </div>
    </el-disclosure>
</nav>
