<flux:main>
    <x-manta.breadcrumb :$breadcrumb />
    <div class="mt-4 flex">
        <div class="flex-grow">
            @if (auth('staff')->user()->developer)
                <x-manta.buttons.large type="add" :href="route($this->module_routes['create'])" />
            @endif
        </div>
        <div class="w-1/5">
            <x-manta.input.search />
        </div>
    </div>
    <x-manta.tables.tabs :$tablistShow :$trashed />
    <flux:table :paginate="$items">
        <flux:table.columns>
            <flux:table.column></flux:table.column>

            <flux:table.column sortable :sorted="$sortBy === 'title'" :direction="$sortDirection"
                wire:click="dosort('title')">
                Titel</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'identifier'" :direction="$sortDirection"
                wire:click="dosort('identifier')">
                Locatie hint</flux:table.column>

        </flux:table.columns>
        <flux:table.rows>
            @foreach ($items as $item)
                <flux:table.row data-id="{{ $item->id }}">

                    <flux:table.cell><x-manta.tables.image :item="$item" /></flux:table.cell>
                    <flux:table.cell>
                        {{ $item->title }}
                    </flux:table.cell>
                    <flux:table.cell>
                        {{ $item->identifier }}
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:button size="sm" icon="eye" href="{{ route('manta-cms.upload.read', $item) }}" />

                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

</flux:main>
