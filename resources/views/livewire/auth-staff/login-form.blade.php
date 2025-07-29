<div>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md">
            <div class="bg-white p-8 rounded-lg shadow-md">
                <x-flux::heading level="1" class="text-center mb-6">
                    {{ __('Staff Login') }}
                </x-flux::heading>

                <form wire:submit="login">
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
                            <x-flux::input
                                label="{{ __('Wachtwoord') }}"
                                name="password"
                                type="password"
                                wire:model="password"
                                required
                            />
                        </div>

                        <div class="flex items-center">
                            <x-flux::checkbox
                                label="{{ __('Onthoud mij') }}"
                                wire:model="remember"
                                name="remember"
                            />
                        </div>

                        <div>
                            <x-flux::button type="submit" class="w-full">
                                {{ __('Inloggen') }}
                            </x-flux::button>
                        </div>

                        <div class="text-center mt-4">
                            <a href="{{ route('flux-cms.staff.forgot-password') }}" class="text-blue-600 hover:underline">
                                {{ __('Wachtwoord vergeten?') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
