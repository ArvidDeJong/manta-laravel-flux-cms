<flux:main container>

    <x-manta.breadcrumb :$breadcrumb />
    <div class="flex items-center justify-between mt-4">
        <div>
            <flux:button icon="plus" href="{{ route('manta-cms.user.create') }}">
                Nieuwe gebruiker
            </flux:button>
        </div>
        <div style="width: 300px">
            <flux:input type="search" wire:model="search" placeholder="Zoeken..." />
        </div>
    </div>
    <x-manta.tables.tabs :$tablistShow :$trashed />

    <flux:table :paginate="$items">
        <flux:table.columns>
            <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection"
                wire:click="dosort('name')">
                Naam</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'email'" :direction="$sortDirection"
                wire:click="dosort('email')">
                Email</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'active'" :direction="$sortDirection"
                wire:click="dosort('active')">
                Actief</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach ($items as $item)
                <flux:table.row data-id="{{ $item->id }}">
                    <flux:table.cell>{{ $item->name }}</flux:table.cell>
                    <flux:table.cell>{{ $item->email }}</flux:table.cell>
                    <flux:table.cell>{!! $item->active
                        ? '<i class="text-green-600 fa-solid fa-check"></i>'
                        : '<i class="text-red-600 fa-solid fa-xmark"></i>' !!}</flux:table.cell>
                    <flux:table.cell>
                        <flux:button icon="eye" href="{{ route('manta-cms.user.read', $item->id) }}"
                            size="sm" />

                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:button size="sm" href="{{ route('manta-cms.user.read', $item) }}" icon="eye" />
                        <x-manta.tables.delete-modal :item="$item" />
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</flux:main>
