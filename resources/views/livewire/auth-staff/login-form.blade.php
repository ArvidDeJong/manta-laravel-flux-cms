<div>
    <div class="flex min-h-screen items-center justify-center bg-gray-100">
        <div class="w-full max-w-md">
            <flux:card class="p-8">
                <div class="mb-8 text-center">
                    <flux:heading size="xl" level="1" class="mb-2">Staff Login</flux:heading>
                    <flux:text class="text-gray-600">Log in om toegang te krijgen tot het CMS</flux:text>
                </div>

                <form wire:submit="login">
                    <div class="space-y-6">
                        <flux:input wire:model="email" label="E-mailadres" type="email" placeholder="je@email.nl"
                            icon="at-symbol" />

                        <flux:input wire:model="password" label="Wachtwoord" type="password" placeholder="Je wachtwoord"
                            icon="key" viewable />

                        <div class="flex items-center justify-between">
                            <flux:checkbox wire:model="remember" label="Onthoud mij" />

                            <flux:link href="{{ route('flux-cms.staff.forgot-password') }}" class="text-sm">
                                Wachtwoord vergeten?
                            </flux:link>
                        </div>

                        <flux:button type="submit" variant="primary" class="w-full" icon="arrow-right">Inloggen
                        </flux:button>
                    </div>
                </form>
            </flux:card>
        </div>
    </div>
</div>
