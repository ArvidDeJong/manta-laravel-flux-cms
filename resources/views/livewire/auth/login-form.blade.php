<div class="flex min-h-screen">
    <div class="flex flex-1 items-center justify-center">
        <div class="w-80 max-w-80 space-y-6">
            <div class="flex justify-center opacity-50">
                <a href="/" class="group flex items-center gap-3">
                    @if (file_exists(public_path('img/logo.png')))
                        <img src="{{ asset('img/logo.png') }}" alt="{{ config('app.name', 'Laravel') }}" class="h-6">
                    @else
                        <div>
                            <svg class="h-4 text-zinc-800 dark:text-white" viewBox="0 0 18 13" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g>
                                    <line x1="1" y1="5" x2="1" y2="10"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"></line>
                                    <line x1="5" y1="1" x2="5" y2="8"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"></line>
                                    <line x1="9" y1="5" x2="9" y2="10"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"></line>
                                    <line x1="13" y1="1" x2="13" y2="12"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"></line>
                                    <line x1="17" y1="5" x2="17" y2="10"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"></line>
                                </g>
                            </svg>
                        </div>
                    @endif

                    <span
                        class="text-xl font-semibold text-zinc-800 dark:text-white">{{ config('app.name', 'Laravel') }}</span>
                </a>
            </div>

            <flux:heading class="text-center" size="xl">Welkom terug</flux:heading>

            <div class="flex flex-col gap-6">
                <flux:input label="E-mailadres" type="email" wire:model.blur="email" placeholder="email@voorbeeld.nl"
                    error="{{ $errors->first('email') }}" />

                <flux:field>
                    <div class="mb-3 flex justify-between">
                        <flux:label>Wachtwoord</flux:label>

                        <flux:link href="{{ route('password.request') }}" variant="subtle" class="text-sm">Wachtwoord
                            vergeten?</flux:link>
                    </div>

                    <flux:input type="password" wire:model.blur="password" placeholder="Je wachtwoord"
                        error="{{ $errors->first('password') }}" />
                </flux:field>

                <flux:checkbox wire:model.live="remember" label="Onthoud mij voor 30 dagen" />

                <flux:button variant="primary" class="w-full" wire:click="login">
                    Inloggen
                </flux:button>
            </div>

            @if (config('manta.user_register') && Route::has('register'))
                <flux:subheading class="text-center">
                    Eerste keer hier? <flux:link href="{{ route('register') }}">Registreer gratis</flux:link>
                </flux:subheading>
            @endif
        </div>
    </div>

    <div class="flex-1 p-4 max-lg:hidden">
        <div class="relative flex h-full w-full flex-col items-start justify-end rounded-lg bg-zinc-900 p-16 text-white"
            style="background-image: url('{{ manta_cms_asset('images/login_background.jpg') }}'); background-size: cover">
            <div class="mb-4 flex gap-2">
                <flux:icon.star variant="solid" />
                <flux:icon.star variant="solid" />
                <flux:icon.star variant="solid" />
                <flux:icon.star variant="solid" />
                <flux:icon.star variant="solid" />
            </div>

            <div class="font-base mb-6 text-3xl italic xl:text-4xl">
                Welkom bij ons platform. Log in om toegang te krijgen tot uw account.
            </div>

            {{-- <div class="flex gap-4">
                <flux:avatar src="https://fluxui.dev/img/demo/caleb.png" fallback="{{ config('app.name', 'Laravel') }}"
                    size="xl" />

                <div class="flex flex-col justify-center font-medium">
                    <div class="text-lg">{{ config('app.name', 'Laravel') }}</div>
                    <div class="text-zinc-300">Inlogportaal</div>
                </div>
            </div> --}}
        </div>
    </div>
</div>
