<div>
    <div class="flex min-h-screen items-center justify-center">
        <div class="w-full max-w-md">
            <flux:card title="E-mail verificatie" color="primary">
                <x-slot:header>
                    <div class="mb-4 flex items-center justify-center">
                        <x-flux::icon icon="shield-check" class="text-primary h-16 w-16" />
                    </div>
                    <h1 class="text-center text-2xl font-semibold text-gray-900">E-mailadres verifiëren</h1>
                </x-slot:header>

                <div class="space-y-6">
                    <div class="text-sm leading-relaxed text-gray-600">
                        <p class="mb-4">Bedankt voor je registratie! Voordat je verder gaat, controleer je e-mail voor
                            een verificatielink.</p>
                        <p>Als je geen e-mail hebt ontvangen, dan kunnen we een nieuwe sturen.</p>
                    </div>

                    @if (session('message'))
                        <flux:callout type="success">
                            {{ session('message') }}
                        </flux:callout>
                    @endif

                    <div class="mt-4">
                        <flux:button wire:click="resendVerification" wire:loading.attr="disabled" color="primary" block>
                            <div wire:loading wire:target="resendVerification" class="mr-2">
                                <x-flux::icon icon="arrow-path" class="h-4 w-4 animate-spin" />
                            </div>
                            Verstuur verificatie e-mail opnieuw
                        </flux:button>
                    </div>

                    <div class="mt-6 text-center text-sm">
                        <flux:button tag="a" href="{{ route('flux-cms.account.logout') }}" color="link">
                            Uitloggen
                        </flux:button>
                    </div>
                </div>
            </flux:card>
        </div>
    </div>
</div>
