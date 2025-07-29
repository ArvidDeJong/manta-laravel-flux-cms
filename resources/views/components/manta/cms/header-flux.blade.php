<flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
    <flux:brand href="#"
        logo="{{ env('DEFAULT_LOGO_HEADER') ? env('DEFAULT_LOGO_HEADER') : '/vendor/manta-cms/manta/img/logo-cutout.png' }}"
        name="Manta" class="max-lg:hidden dark:hidden" />
    <flux:brand href="#"
        logo="{{ env('DEFAULT_LOGO_HEADER') ? env('DEFAULT_LOGO_HEADER') : '/vendor/manta-cms/manta/img/logo-cutout.png' }}"
        name="Manta" class="hidden max-lg:!hidden dark:flex" />
    <flux:navbar class="max-lg:hidden">
        <flux:navbar.item icon="home" href="{{ route('manta-cms.dashboard') }}">Home</flux:navbar.item>
        <flux:separator vertical variant="subtle" class="my-2" />

        @if ($generalModules->count() > 0)
            <flux:dropdown class="max-lg:hidden">
                <flux:navbar.item icon-trailing="chevron-down">Modules</flux:navbar.item>
                <flux:navmenu>
                    @foreach ($generalModules as $module)
                        <flux:navmenu.item href="{{ $module->route ? route($module->route) : $module->url }}">
                            {!! $module->title !!}</flux:navmenu.item>
                    @endforeach
                </flux:navmenu>
            </flux:dropdown>
        @endif

        @if ($webshopModules->count() > 0)
            <flux:dropdown class="max-lg:hidden">
                <flux:navbar.item icon-trailing="chevron-down">Webshop</flux:navbar.item>
                <flux:navmenu>
                    @foreach ($webshopModules as $module)
                        <flux:navmenu.item href="{{ $module->route ? route($module->route) : $module->url }}">
                            {!! $module->title !!}</flux:navmenu.item>
                    @endforeach
                </flux:navmenu>
            </flux:dropdown>
        @endif

        @if ($toolsModules->count() > 0)
            <flux:dropdown class="max-lg:hidden">
                <flux:navbar.item icon-trailing="chevron-down">Tools</flux:navbar.item>
                <flux:navmenu>
                    @foreach ($toolsModules as $module)
                        <flux:navmenu.item href="{{ $module->route ? route($module->route) : $module->url }}">
                            {!! $module->title !!}</flux:navmenu.item>
                    @endforeach
                </flux:navmenu>
            </flux:dropdown>
        @endif

        @if ($devModules->count() > 0)
            <flux:dropdown class="max-lg:hidden">
                <flux:navbar.item icon-trailing="chevron-down">Dev</flux:navbar.item>
                <flux:navmenu>
                    @foreach ($devModules as $module)
                        <flux:navmenu.item href="{{ $module->route ? route($module->route) : $module->url }}">
                            {!! $module->title !!}</flux:navmenu.item>
                    @endforeach
                </flux:navmenu>
            </flux:dropdown>
        @endif
    </flux:navbar>
    <flux:spacer />
    <flux:navbar.item icon="globe-alt" href="{{ env('APP_URL') }}" target="_blank">Website</flux:navbar.item>
    <flux:dropdown class="max-lg:hidden">
        <flux:navbar.item icon-trailing="chevron-down" icon="user">{{ auth('staff')->user()->name }}
        </flux:navbar.item>
        <flux:navmenu>
            <flux:menu.item icon="computer-desktop" href="https://get.teamviewer.com/arvid" target="_blank">
                Teamviewer
            </flux:menu.item>
            <flux:navmenu.item href="{{ route('manta-cms.logout') }}" icon="arrow-right-start-on-rectangle">
                Uitloggen
            </flux:navmenu.item>
        </flux:navmenu>
    </flux:dropdown>
</flux:header>
