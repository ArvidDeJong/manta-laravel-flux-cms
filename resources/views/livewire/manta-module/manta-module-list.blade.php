<flux:main container>

    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('manta-cms.dashboard') }}">Dashboard</flux:breadcrumbs.item>

        <flux:breadcrumbs.item>Modules</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="mt-4 flex items-center justify-between">
        <div>
            <flux:button icon="plus" href="{{ route('manta-cms.manta-module.create') }}">
                Toevoegen
            </flux:button>
        </div>
        <div style="width: 300px">
            <flux:input type="search" wire:model="search" placeholder="Zoeken..." />
        </div>
    </div>
    <x-manta.tables.tabs :$tablistShow :$trashed />

    <flux:table :paginate="$items">
        <flux:table.columns>
            <flux:table.column>#</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection"
                wire:click="dosort('name')">
                Naam</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'title'" :direction="$sortDirection"
                wire:click="dosort('title')">
                Titel</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'type'" :direction="$sortDirection"
                wire:click="dosort('type')">
                Type</flux:table.column>
            <flux:table.column>Route</flux:table.column>
            <flux:table.column>Actief</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'sort'" :direction="$sortDirection"
                wire:click="dosort('sort')">
                Sortering</flux:table.column>
            <flux:table.column>Acties</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($items as $key => $item)
                <flux:table.row data-id="{{ $item->id }}" wire:key="{{ $item->id }}">
                    <flux:table.cell>{{ $key + 1 }}</flux:table.cell>
                    <flux:table.cell>
                        <div class="flex items-center gap-2">
                            @if ($item->icon)
                                <flux:icon :name="$item->icon" class="h-4 w-4" />
                            @endif
                            {{ $item->name }}
                        </div>
                    </flux:table.cell>
                    <flux:table.cell>{{ $item->title }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge
                            :color="match($item->type) {
                                                                                                                                                                            'cms' => 'blue',
                                                                                                                                                                            'webshop' => 'green', 
                                                                                                                                                                            'tools' => 'orange',
                                                                                                                                                                            'dev' => 'red',
                                                                                                                                                                            default => 'gray'
                                                                                                                                                                        }">
                            {{ ucfirst($item->type) }}
                        </flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        @if ($item->route)
                            <code class="rounded bg-gray-100 px-1 text-xs">{{ $item->route }}</code>
                        @elseif($item->url)
                            <a href="{{ $item->url }}" target="_blank" class="text-xs text-blue-600 hover:underline">
                                {{ Str::limit($item->url, 30) }}
                            </a>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </flux:table.cell>
                    <flux:table.cell>
                        {!! $item->active
                            ? '<i class="text-green-600 fa-solid fa-check"></i>'
                            : '<i class="text-red-600 fa-solid fa-xmark"></i>' !!}
                    </flux:table.cell>
                    <flux:table.cell>{{ $item->sort }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:button size="sm" href="{{ route('manta-cms.manta-module.read', $item) }}"
                            icon="eye" />
                        <x-manta.tables.delete-modal :item="$item" />
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

</flux:main>
