<div>
    <div class="flex min-h-screen items-center justify-center bg-gray-100">
        <div class="w-full max-w-md">
            <flux:card class="p-8">
                <div class="mb-8 text-center">
                    <flux:heading size="xl" level="1" class="mb-2">E-mail verifiëren</flux:heading>
                    <flux:text class="text-gray-600">Controleer je inbox om je account te activeren</flux:text>
                </div>

                @if (session('status') === 'verification-link-sent')
                    <flux:callout variant="success" class="mb-6">
                        <flux:icon name="check-circle" class="h-5 w-5" />
                        Een nieuwe verificatielink is verzonden naar je e-mailadres.
                    </flux:callout>
                @endif

                <div class="mb-8">
                    <flux:text class="text-gray-600 leading-relaxed">
                        Bedankt voor je registratie! Voordat je kunt beginnen, moet je je e-mailadres verifiëren door op de link te klikken die we zojuist naar je hebben gemaild.
                    </flux:text>
                    <flux:text class="mt-4 text-gray-600">
                        Geen e-mail ontvangen? Controleer je spam-map of vraag een nieuwe link aan.
                    </flux:text>
                </div>

                <div class="space-y-6">
                    <form wire:submit="resend">
                        <flux:button 
                            type="submit" 
                            variant="primary" 
                            class="w-full"
                            icon="paper-airplane"
                        >
                            Verstuur verificatie e-mail opnieuw
                        </flux:button>
                    </form>

                    <div class="text-center">
                        <flux:link href="{{ route('flux-cms.staff.logout') }}" class="inline-flex items-center gap-2 text-sm">
                            <flux:icon name="arrow-right-start-on-rectangle" class="h-4 w-4" />
                            Uitloggen
                        </flux:link>
                    </div>
                </div>
            </flux:card>
        </div>
    </div>
</div>
