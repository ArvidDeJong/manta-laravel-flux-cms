<div>
    <div class="flex min-h-screen items-center justify-center bg-gray-100">
        <div class="w-full max-w-md">
            <flux:card class="p-8">
                <div class="mb-8 text-center">
                    <flux:heading size="xl" level="1" class="mb-2">Wachtwoord vergeten</flux:heading>
                    <flux:text class="text-gray-600">Reset je wachtwoord om toegang te krijgen tot het CMS</flux:text>
                </div>

                @if ($emailSent)
                    <flux:callout variant="success" class="mb-6">
                        <flux:icon name="check-circle" class="h-5 w-5" />
                        We hebben een e-mail verzonden met instructies om je wachtwoord te resetten.
                    </flux:callout>

                    <div class="text-center">
                        <flux:link href="{{ route('flux-cms.staff.login') }}" class="inline-flex items-center gap-2">
                            <flux:icon name="arrow-left" class="h-4 w-4" />
                            Terug naar inloggen
                        </flux:link>
                    </div>
                @else
                    <form wire:submit.prevent="sendResetLink">
                        <div class="space-y-6">
                            <flux:input wire:model="email" label="E-mailadres" type="email" placeholder="je@email.nl"
                                icon="at-symbol" autofocus />

                            <flux:button type="submit" variant="primary" class="w-full" icon="paper-airplane">
                                Verstuur reset link
                            </flux:button>

                            <div class="text-center">
                                <flux:link href="{{ route('flux-cms.staff.login') }}"
                                    class="inline-flex items-center gap-2 text-sm">
                                    <flux:icon name="arrow-left" class="h-4 w-4" />
                                    Terug naar inloggen
                                </flux:link>
                            </div>
                        </div>
                    </form>
                @endif
            </flux:card>
        </div>
    </div>
</div>
