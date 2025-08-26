<flux:main container>
    <div class="mb-8">
        <flux:heading size="xl" level="1">Dashboard</flux:heading>
        <flux:text class="mt-2 text-gray-600">Overzicht van je CMS statistieken en activiteiten</flux:text>
    </div>

    @if (env('APP_ENV') === 'local')
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            <flux:card class="p-6">
                <div class="flex items-center">
                    <div class="bg-primary-50 flex h-12 w-12 items-center justify-center rounded-full">
                        <flux:icon name="chart-bar" class="text-primary-600 h-6 w-6" />
                    </div>
                    <div class="ml-4">
                        <flux:heading size="sm" class="text-gray-700">Statistieken</flux:heading>
                        <flux:text size="2xl" class="font-semibold">1,234</flux:text>
                    </div>
                </div>
            </flux:card>

            <flux:card class="p-6">
                <div class="flex items-center">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-50">
                        <flux:icon name="users" class="h-6 w-6 text-green-600" />
                    </div>
                    <div class="ml-4">
                        <flux:heading size="sm" class="text-gray-700">Gebruikers</flux:heading>
                        <flux:text size="2xl" class="font-semibold">512</flux:text>
                    </div>
                </div>
            </flux:card>

            <flux:card class="p-6">
                <div class="flex items-center">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-50">
                        <flux:icon name="document-text" class="h-6 w-6 text-blue-600" />
                    </div>
                    <div class="ml-4">
                        <flux:heading size="sm" class="text-gray-700">Inhoud</flux:heading>
                        <flux:text size="2xl" class="font-semibold">89</flux:text>
                    </div>
                </div>
            </flux:card>
        </div>
    @endif
</flux:main>
