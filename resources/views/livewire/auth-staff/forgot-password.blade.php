<div>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md">
            <div class="bg-white p-8 rounded-lg shadow-md">
                <x-flux::heading level="1" class="text-center mb-6">
                    {{ __('Wachtwoord vergeten') }}
                </x-flux::heading>

                @if ($emailSent)
                    <x-flux::callout type="success" class="mb-6">
                        {{ __('We hebben een e-mail verzonden met instructies om je wachtwoord te resetten.') }}
                    </x-flux::callout>
                @else
                    <p class="text-gray-600 mb-6">
                        {{ __('Vul je e-mailadres in en we sturen je een link om je wachtwoord te resetten.') }}
                    </p>

                    <form wire:submit="sendResetLink">
                        <div class="space-y-6">
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
                                <x-flux::button type="submit" class="w-full">
                                    {{ __('Verstuur reset link') }}
                                </x-flux::button>
                            </div>

                            <div class="text-center mt-4">
                                <a href="{{ route('flux-cms.staff.login') }}" class="text-blue-600 hover:underline">
                                    {{ __('Terug naar inloggen') }}
                                </a>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
