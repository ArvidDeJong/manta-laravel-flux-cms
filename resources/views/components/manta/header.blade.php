    <header class="text-white bg-gray-800">
        @php
            $modules = collect(cms_config('manta')['modules']);
        @endphp
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <img class="h-8"
                        src="{{ env('DEFAULT_LOGO_HEADER') ? env('DEFAULT_LOGO_HEADER') : '/vendor/manta-cms/manta/img/logo-cutout.png' }}"
                        alt="Logo">
                    <nav class="relative flex ml-6 space-x-4">
                        <x-manta.header-link title="Home" href="{{ route('cms.dashboard') }}" />
                        <div class="relative inline-block group">
                            <button
                                class="flex items-center px-3 py-2 text-sm font-medium rounded-md hover:bg-gray-700 focus:bg-gray-700 focus:outline-none">
                                Modules
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <ul class="absolute z-10 hidden w-[270px] pt-1 text-gray-700 group-hover:block">
                                @foreach ($modules->where('menu', 'general') as $module)
                                    @if ($module['active'] == 'true')
                                        <x-manta.header-link-sub title="{!! $module['title'] !!}"
                                            href="{{ route($module['route']) }}" location="{{ $module['location'] }}" />
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                        <div class="relative inline-block group">
                            <button
                                class="flex items-center px-3 py-2 text-sm font-medium rounded-md hover:bg-gray-700 focus:bg-gray-700 focus:outline-none">
                                Tools
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <ul class="absolute z-10 hidden pt-1 text-gray-700 group-hover:block">
                                @foreach ($modules->where('menu', 'tools') as $module)
                                    @if ($module['active'] == 'true')
                                        <x-manta.header-link-sub title="{!! $module['title'] !!}"
                                            href="{{ route($module['route']) }}" location="{{ $module['location'] }}" />
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </nav>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ env('APP_URL') }}" target="_blank"
                        class="px-3 py-2 text-sm font-medium rounded-md hover:bg-gray-700"><i
                            class="fa-light fa-globe-pointer"></i> Website</a>
                    <div class="relative inline-block group">
                        <button
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-md hover:bg-gray-700 focus:bg-gray-700 focus:outline-none">
                            <i class="fa-solid fa-user"></i>&nbsp; {{ auth('staff')->user()->name }}
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <ul class="absolute z-10 hidden pt-1 text-gray-700 group-hover:block">
                            <x-manta.header-link-sub title="Uitloggen" href="{{ route('logout') }}" location="single" />
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>
