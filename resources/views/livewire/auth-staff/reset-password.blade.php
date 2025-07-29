<div>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md">
            <div class="bg-white p-8 rounded-lg shadow-md">
                <x-flux::heading level="1" class="text-center mb-6">
                    {{ __('Wachtwoord resetten') }}
                </x-flux::heading>

                <form wire:submit="resetPassword">
                    <div class="space-y-6">
                        <input type="hidden" wire:model="token">

                        <div>
                            <x-flux::input
                                label="{{ __('E-mail') }}"
                                name="email"
                                type="email"
                                wire:model="email"
                                required
                                autofocus
                            />
                            @error('email') <div class="mt-1 text-red-500 text-sm">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <x-flux::input
                                label="{{ __('Nieuw wachtwoord') }}"
                                name="password"
                                type="password"
                                wire:model="password"
                                required
                            />
                            @error('password') <div class="mt-1 text-red-500 text-sm">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <x-flux::input
                                label="{{ __('Bevestig wachtwoord') }}"
                                name="password_confirmation"
                                type="password"
                                wire:model="password_confirmation"
                                required
                            />
                        </div>

                        <div>
                            <x-flux::button type="submit" class="w-full">
                                {{ __('Wachtwoord resetten') }}
                            </x-flux::button>
                        </div>

                        <div class="text-center mt-4">
                            <a href="{{ route('flux-cms.staff.login') }}" class="text-blue-600 hover:underline">
                                {{ __('Terug naar inloggen') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
