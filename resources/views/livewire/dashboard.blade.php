<div>
    <div class="flex justify-between items-center mb-6">
        <x-flux::heading level="1">Dashboard</x-flux::heading>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="p-6 bg-white shadow-sm rounded-lg">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-primary-50 text-primary-600">
                    <x-flux::icon name="chart-bar" class="h-8 w-8" />
                </div>
                <div class="ml-4">
                    <h3 class="font-medium text-gray-700">Statistieken</h3>
                    <p class="text-2xl font-semibold">1,234</p>
                </div>
            </div>
        </div>

        <div class="p-6 bg-white shadow-sm rounded-lg">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-50 text-green-600">
                    <x-flux::icon name="users" class="h-8 w-8" />
                </div>
                <div class="ml-4">
                    <h3 class="font-medium text-gray-700">Gebruikers</h3>
                    <p class="text-2xl font-semibold">512</p>
                </div>
            </div>
        </div>

        <div class="p-6 bg-white shadow-sm rounded-lg">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-50 text-blue-600">
                    <x-flux::icon name="document-text" class="h-8 w-8" />
                </div>
                <div class="ml-4">
                    <h3 class="font-medium text-gray-700">Inhoud</h3>
                    <p class="text-2xl font-semibold">89</p>
                </div>
            </div>
        </div>
    </div>
</div>
