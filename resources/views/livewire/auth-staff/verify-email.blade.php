<div>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md">
            <div class="bg-white p-8 rounded-lg shadow-md">
                <x-flux::heading level="1" class="text-center mb-6">
                    {{ __('E-mail verifiëren') }}
                </x-flux::heading>

                @if (session('status') === 'verification-link-sent')
                    <x-flux::callout type="success" class="mb-6">
                        {{ __('Een nieuwe verificatielink is verzonden naar je e-mailadres.') }}
                    </x-flux::callout>
                @endif

                <div class="mb-6 text-gray-600">
                    {{ __('Bedankt voor je registratie! Voordat je kunt beginnen, moet je je e-mailadres verifiëren door op de link te klikken die we zojuist naar je hebben gemaild. Als je geen e-mail hebt ontvangen, sturen we je graag een nieuwe.') }}
                </div>

                <div class="space-y-6">
                    <form wire:submit="resend">
                        <div class="text-center">
                            <x-flux::button type="submit" class="w-full">
                                {{ __('Verstuur verificatie e-mail opnieuw') }}
                            </x-flux::button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <a href="{{ route('flux-cms.staff.logout') }}" class="text-blue-600 hover:underline">
                            {{ __('Uitloggen') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
