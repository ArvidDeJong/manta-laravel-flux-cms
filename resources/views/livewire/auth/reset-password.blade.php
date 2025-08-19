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

            <flux:heading class="text-center" size="xl">Nieuw wachtwoord instellen</flux:heading>

            <div class="flex flex-col gap-6">
                <flux:input label="E-mailadres" type="email" wire:model="email" placeholder="email@voorbeeld.nl"
                    error="{{ $errors->first('email') }}" readonly />

                <flux:input label="Nieuw wachtwoord" type="password" wire:model="password" viewable
                    placeholder="Minimaal 8 tekens" error="{{ $errors->first('password') }}" />

                <flux:input label="Bevestig nieuw wachtwoord" type="password" wire:model="password_confirmation"
                    placeholder="Herhaal wachtwoord" error="{{ $errors->first('password_confirmation') }}" viewable />

                <flux:button variant="primary" class="w-full" wire:click="resetPassword" wire:loading.attr="disabled">
                    <span wire:loading.remove>Wachtwoord resetten</span>
                    <span wire:loading>Bezig met resetten...</span>
                </flux:button>
            </div>

            <flux:subheading class="text-center">
                <flux:link href="{{ route('login') }}">Terug naar inloggen</flux:link>
            </flux:subheading>
        </div>
    </div>

    <div class="flex-1 p-4 max-lg:hidden">
        <div class="relative flex h-full w-full flex-col items-start justify-end rounded-lg bg-zinc-900 p-16 text-white"
            style="background-image: url('{{ asset('img/login_background.jpg') }}'); background-size: cover">
            <div class="mb-4 flex gap-2">
                <flux:icon.star variant="solid" />
                <flux:icon.star variant="solid" />
                <flux:icon.star variant="solid" />
                <flux:icon.star variant="solid" />
                <flux:icon.star variant="solid" />
            </div>

            <div class="font-base mb-6 text-3xl italic xl:text-4xl">
                Stel een nieuw wachtwoord in voor je account.
            </div>

            {{-- <div class="flex gap-4">
                <flux:avatar src="{{ asset('img/avatar.png') }}" fallback="{{ config('app.name', 'Laravel') }}"
                    size="xl" />

                <div class="flex flex-col justify-center font-medium">
                    <div class="text-lg">{{ config('app.name', 'Laravel') }}</div>
                    <div class="text-zinc-300">Wachtwoord herstel</div>
                </div>
            </div> --}}
        </div>
    </div>
</div>
