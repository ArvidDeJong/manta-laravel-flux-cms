<flux:main container>
    <x-manta.breadcrumb :$breadcrumb />
    <div class="mt-4 flex items-center justify-between">
        <div>

        </div>
        <div style="width: 300px">
            <flux:input type="search" wire:model="search" placeholder="Zoeken..." />
        </div>
    </div>
    <x-manta.tables.tabs :$tablistShow :$trashed />
    <flux:table :paginate="$items">
        <flux:table.columns>
            <flux:table.column>#</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'title'" :direction="$sortDirection"
                wire:click="dosort('title')">
                Titel</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'route'" :direction="$sortDirection"
                wire:click="dosort('route')">
                Route</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'locale'" :direction="$sortDirection"
                wire:click="dosort('locale')">
                Taal</flux:table.column>
            <flux:table.column>SEO Titel</flux:table.column>
            <flux:table.column>SEO Beschrijving</flux:table.column>
            <flux:table.column>Acties</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach ($items as $key => $item)
                <flux:table.row data-id="{{ $item->id }}" wire:key="{{ $item->id }}">
                    <flux:table.cell>{{ $key + 1 }}</flux:table.cell>
                    <flux:table.cell>{{ $item->title }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge size="sm" color="zinc">{{ $item->route }}</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:badge size="sm" color="blue">{{ strtoupper($item->locale) }}</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        <div class="max-w-xs truncate" title="{{ $item->seo_title }}">
                            {{ Str::limit($item->seo_title, 30) }}
                        </div>
                    </flux:table.cell>
                    <flux:table.cell>
                        <div class="max-w-xs truncate" title="{{ $item->seo_description }}">
                            {{ Str::limit($item->seo_description, 30) }}
                        </div>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:button size="sm" href="{{ route($this->module_routes['update'], $item) }}"
                            icon="pencil" />
                        <x-manta.tables.delete-modal :item="$item" />
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</flux:main>
