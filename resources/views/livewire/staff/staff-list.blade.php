<flux:main container>
    <x-manta.breadcrumb :$breadcrumb />
    <div class="flex items-center justify-between mt-4">
        <div>
            <flux:button icon="plus" href="{{ route('manta-cms.staff.create') }}">
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
            <flux:table.column sortable :sorted="$sortBy === 'email'" :direction="$sortDirection"
                wire:click="dosort('email')">
                Email</flux:table.column>
            <flux:table.column>Actief</flux:table.column>
            <flux:table.column>Admin</flux:table.column>
            <flux:table.column>Developer</flux:table.column>
            <flux:table.column>Laatste login</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach ($items as $key => $item)
                <flux:table.row data-id="{{ $item->id }}">
                    <flux:table.cell>{{ $key + 1 }}</flux:table.cell>
                    <flux:table.cell>{{ $item->name }}</flux:table.cell>
                    <flux:table.cell>{{ $item->email }}</flux:table.cell>
                    <flux:table.cell>
                        {!! $item->active
                            ? '<i class="text-green-600 fa-solid fa-check"></i>'
                            : '<i class="text-red-600 fa-solid fa-xmark"></i>' !!}
                    </flux:table.cell>
                    <flux:table.cell>
                        {!! $item->admin
                            ? '<i class="text-green-600 fa-solid fa-check"></i>'
                            : '<i class="text-red-600 fa-solid fa-xmark"></i>' !!}
                    </flux:table.cell>
                    <flux:table.cell>
                        {!! $item->developer
                            ? '<i class="text-green-600 fa-solid fa-check"></i>'
                            : '<i class="text-red-600 fa-solid fa-xmark"></i>' !!}
                    </flux:table.cell>
                    <flux:table.cell>
                        {{ $item->lastLogin ? Carbon\Carbon::parse($item->lastLogin)->format('d-m-Y H:i') : null }}
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:button size="sm" href="{{ route('manta-cms.staff.rights', $item) }}"
                            icon="key" />
                        <flux:button size="sm" href="{{ route('manta-cms.staff.read', $item) }}" icon="eye" />
                        @if (Auth::user('staff')->id != $item->id)
                            <x-manta.tables.delete-modal :item="$item" />
                        @endif
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</flux:main>
