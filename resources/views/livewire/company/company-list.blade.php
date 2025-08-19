<flux:main container>
    <x-manta.breadcrumb :$breadcrumb />
    <div class="mt-4 flex items-center justify-between">
        <div>
            <flux:button icon="plus" href="{{ route('manta-cms.company.create') }}">
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
            @if ($this->fields['uploads']['active'])
                <flux:table.column></flux:table.column>
            @endif
            <flux:table.column>#</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'company'" :direction="$sortDirection"
                wire:click="dosort('company')">
                Bedrijfsnaam</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'lastname'" :direction="$sortDirection"
                wire:click="dosort('lastname')">
                Achternaam</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'firstnames'" :direction="$sortDirection"
                wire:click="dosort('firstnames')">
                Voornamen</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'city'" :direction="$sortDirection"
                wire:click="dosort('city')">
                Plaats</flux:table.column>
            <flux:table.column>Actief</flux:table.column>
            <flux:table.column>Telefoon</flux:table.column>
            <flux:table.column>Acties</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($items as $key => $item)
                <flux:table.row data-id="{{ $item->id }}" wire:key="{{ $item->id }}">
                    @if ($this->fields['uploads']['active'])
                        <flux:table.cell><x-manta.tables.image :item="$item->image" /></flux:table.cell>
                    @endif
                    <flux:table.cell>{{ $key + 1 }}</flux:table.cell>
                    <flux:table.cell>{{ $item->company }}</flux:table.cell>
                    <flux:table.cell>{{ $item->lastname }}</flux:table.cell>
                    <flux:table.cell>{{ $item->firstnames }}</flux:table.cell>
                    <flux:table.cell>{{ $item->city }}</flux:table.cell>
                    <flux:table.cell>
                        {!! $item->active
                            ? '<i class="text-green-600 fa-solid fa-check"></i>'
                            : '<i class="text-red-600 fa-solid fa-xmark"></i>' !!}
                    </flux:table.cell>
                    <flux:table.cell>{{ $item->phone }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:button size="sm" href="{{ route('manta-cms.company.read', $item) }}"
                            icon="eye" />
                        <x-manta.tables.delete-modal :item="$item" />
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

</flux:main>
