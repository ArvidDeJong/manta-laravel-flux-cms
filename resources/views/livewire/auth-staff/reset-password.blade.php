<div>
    <div class="flex min-h-screen items-center justify-center bg-gray-100">
        <div class="w-full max-w-md">
            <flux:card class="p-8">
                <div class="mb-8 text-center">
                    <flux:heading size="xl" level="1" class="mb-2">Wachtwoord resetten</flux:heading>
                    <flux:text class="text-gray-600">Voer je nieuwe wachtwoord in om toegang te herstellen</flux:text>
                </div>

                <form wire:submit="resetPassword">
                    <input type="hidden" wire:model="token">
                    
                    <div class="space-y-6">
                        <flux:input
                            wire:model="email"
                            label="E-mailadres"
                            type="email"
                            placeholder="je@email.nl"
                            icon="at-symbol"
                            autofocus
                        />

                        <flux:input
                            wire:model="password"
                            label="Nieuw wachtwoord"
                            type="password"
                            placeholder="Minimaal 8 karakters"
                            icon="lock-closed"
                            viewable
                        />

                        <flux:input
                            wire:model="password_confirmation"
                            label="Bevestig wachtwoord"
                            type="password"
                            placeholder="Herhaal je nieuwe wachtwoord"
                            icon="lock-closed"
                        />

                        <flux:button 
                            type="submit" 
                            variant="primary" 
                            class="w-full"
                            icon="key"
                        >
                            Wachtwoord resetten
                        </flux:button>
                        
                        <div class="text-center">
                            <flux:link href="{{ route('flux-cms.staff.login') }}" class="inline-flex items-center gap-2 text-sm">
                                <flux:icon name="arrow-left" class="h-4 w-4" />
                                Terug naar inloggen
                            </flux:link>
                        </div>
                    </div>
                </form>
            </flux:card>
        </div>
    </div>
</div>
