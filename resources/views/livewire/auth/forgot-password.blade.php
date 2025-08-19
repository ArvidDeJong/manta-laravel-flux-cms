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

            <flux:heading class="text-center" size="xl">Wachtwoord vergeten</flux:heading>

            <flux:subheading class="text-center">
                Vul je e-mailadres in en we sturen je een link om je wachtwoord opnieuw in te stellen.
            </flux:subheading>

            <div class="flex flex-col gap-6">
                <flux:input label="E-mailadres" type="email" wire:model="email" placeholder="email@voorbeeld.nl"
                    error="{{ $errors->first('email') }}" />

                <div class="flex flex-col space-y-3">
                    <flux:button variant="primary" class="w-full" wire:click="sendResetLink"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove>Link versturen</span>
                        <span wire:loading>Bezig met versturen...</span>
                    </flux:button>

                    <flux:button variant="subtle" class="w-full" href="{{ route('login') }}">
                        Terug naar inloggen
                    </flux:button>
                </div>
            </div>
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
                Wachtwoord vergeten? Geen probleem, we helpen je graag verder.
            </div>

            {{-- <div class="flex gap-4">
                <flux:avatar src="{{ asset('img/avatar.png') }}" fallback="{{ config('app.name', 'Laravel') }}"
                    size="xl" />

                <div class="flex flex-col justify-center font-medium">
                    <div class="text-lg">{{ config('app.name', 'Laravel') }}</div>
                    <div class="text-zinc-300">Wachtwoordherstel</div>
                </div>
            </div> --}}
        </div>
    </div>
</div>
