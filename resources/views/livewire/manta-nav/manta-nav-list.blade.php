<flux:main container>
    <x-manta.breadcrumb :$breadcrumb />
    <div class="flex items-center justify-between mt-4">
        <div>
            <flux:modal.trigger name="edit-profile">
                <flux:button icon="plus">
                    Nieuwe navigatie
                </flux:button>
            </flux:modal.trigger>
            <flux:modal name="edit-profile" class="md:w-96">
                <div class="space-y-6">
                    <div>
                        <flux:heading size="lg">Nieuwe navigatie</flux:heading>
                        <flux:text class="mt-2">Make changes to your personal details.</flux:text>
                    </div>
                    <form wire:submit.prevent="store">
                        <flux:input label="Titel" placeholder="Titel" wire:model="title" />
                        <flux:select label="Type" placeholder="Type" wire:model.live="type">
                            <option value="">Selecteer een type</option>
                            <option value="module">Modules</option>
                            <option value="webshop">Webshop</option>
                            <option value="tool">Tools</option>
                            <option value="dev">Dev</option>
                        </flux:select>
                        @if (!$url)
                            <flux:select label="Route" placeholder="Route" wire:model.live="route">
                                <option value="">Selecteer een route</option>
                                @foreach ($routes as $route)
                                    <option value="{{ $route->name }}">{{ $route->name }}</option>
                                @endforeach
                            </flux:select>
                        @endif
                        @if (!$route)
                            <flux:input label="URL" placeholder="URL" wire:model.live="url" />
                        @endif
                        <div class="flex">
                            <flux:spacer />
                            <flux:button type="submit" variant="primary">Opslaan</flux:button>
                        </div>
                    </form>
                </div>
            </flux:modal>
        </div>
        <div style="width: 300px">
            <flux:input type="search" wire:model.live="search" placeholder="Zoeken..." />
        </div>
    </div>
    <flux:table :paginate="$items" class="mt-8">
        <flux:table.columns>
            <flux:table.column sortable :sorted="$sortBy === 'title'" :direction="$sortDirection"
                wire:click="sortBy('title')">
                Titel
            </flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'type'" :direction="$sortDirection"
                wire:click="sortBy('type')">
                Type
            </flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'route'" :direction="$sortDirection"
                wire:click="sortBy('route')">
                Route
            </flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'url'" :direction="$sortDirection"
                wire:click="sortBy('url')">
                URL
            </flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'locale'" :direction="$sortDirection"
                wire:click="sortBy('locale')">
                Taal
            </flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'sort'" :direction="$sortDirection"
                wire:click="sortBy('sort')">
                Volgorde
            </flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'active'" :direction="$sortDirection"
                wire:click="sortBy('active')">
                Actief
            </flux:table.column>
            <flux:table.column>
                Acties
            </flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach ($items as $item)
                <flux:table.row data-id="{{ $item->id }}">
                    <flux:table.cell>
                        <div class="font-medium">{{ $item->title }}</div>
                        @if ($item->pid)
                            <div class="text-sm text-gray-500">
                                <flux:icon name="arrow-turn-down-right" class="inline w-3 h-3" />
                                Submenu van: {{ $item->parent?->title }}
                            </div>
                        @endif
                    </flux:table.cell>
                    <flux:table.cell>
                        @if ($item->type)
                            <flux:badge color="blue" size="sm">{{ $item->type }}</flux:badge>
                        @endif
                    </flux:table.cell>
                    <flux:table.cell>
                        @if ($item->route)
                            <code class="px-2 py-1 text-sm bg-gray-100 rounded">{{ $item->route }}</code>
                        @endif
                    </flux:table.cell>
                    <flux:table.cell>
                        @if ($item->url)
                            <a href="{{ $item->url }}" target="_blank" class="text-blue-600 hover:underline">
                                {{ Str::limit($item->url, 30) }}
                                <flux:icon name="arrow-top-right-on-square" class="inline w-3 h-3 ml-1" />
                            </a>
                        @endif
                    </flux:table.cell>
                    <flux:table.cell>
                        @if ($item->locale)
                            <flux:badge color="gray" size="sm">{{ strtoupper($item->locale) }}</flux:badge>
                        @endif
                    </flux:table.cell>
                    <flux:table.cell>
                        <span class="font-mono text-sm">{{ $item->sort }}</span>
                    </flux:table.cell>
                    <flux:table.cell>
                        @if ($tablistShow !== 'trashed')
                            <flux:button wire:click="toggleActive({{ $item->id }})" size="sm"
                                variant="{{ $item->active ? 'filled' : 'ghost' }}"
                                color="{{ $item->active ? 'green' : 'red' }}"
                                icon="{{ $item->active ? 'check' : 'x-mark' }}">
                                {{ $item->active ? 'Actief' : 'Inactief' }}
                            </flux:button>
                        @else
                            {!! $item->active
                                ? '<i class="text-green-600 fa-solid fa-check"></i>'
                                : '<i class="text-red-600 fa-solid fa-xmark"></i>' !!}
                        @endif
                    </flux:table.cell>
                    <flux:table.cell>
                        <x-manta.tables.delete-modal :item="$item" />
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</flux:main>
