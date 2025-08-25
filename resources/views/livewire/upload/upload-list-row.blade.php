<?php

namespace Manta\FluxCMS\Livewire\Upload;

use Manta\FluxCMS\Models\Upload;
use Manta\Traits\MantaPagerowTrait;

new class extends \Livewire\Volt\Component {
    public Upload $item;

    use MantaPagerowTrait;
};
?>
<flux:table.row data-id="{{ $item->id }}">

    <flux:table.cell><x-manta.tables.image :item="$item" /></flux:table.cell>
    <flux:table.cell>
        {{ $item->title }}
    </flux:table.cell>
    <flux:table.cell>
        {{ $item->identifier }}
    </flux:table.cell>

    {{-- <x-manta.flux.manta-flux-delete :$item :$route_name :$moduleClass :$fields :translatable="false" /> --}}
</flux:table.row>
