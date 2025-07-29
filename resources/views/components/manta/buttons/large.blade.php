@props(['href', 'type', 'title' => '', 'click' => null])
@php
    $types = [
        'add' => [
            'icon' => 'plus',
            'defaultTitle' => 'Toevoegen',
            'bg' => 'bg-green-500',
            'hover' => 'hover:bg-green-600',
        ],
        'edit' => [
            'icon' => 'pencil',
            'defaultTitle' => 'Aanpassen',
            'bg' => 'bg-yellow-500',
            'hover' => 'hover:bg-yellow-600',
        ],
        'read' => [
            'icon' => 'eye',
            'defaultTitle' => 'Lezen',
            'bg' => 'bg-yellow-500',
            'hover' => 'hover:bg-yellow-600',
        ],
        'tree' => [
            'icon' => 'numbered-list',
            'defaultTitle' => 'Boomstructuur',
            'bg' => 'bg-yellow-500',
            'hover' => 'hover:bg-yellow-600',
        ],
        'list' => [
            'icon' => 'list-bullet',
            'defaultTitle' => 'Lijst',
            'bg' => 'bg-yellow-500',
            'hover' => 'hover:bg-yellow-600',
        ],
        'gear' => [
            'icon' => 'cog-6-tooth',
            'defaultTitle' => 'Instellingen',
            'bg' => 'bg-yellow-500',
            'hover' => 'hover:bg-yellow-600',
        ],
        'maps' => [
            'icon' => 'map',
            'defaultTitle' => 'Kaart',
            'bg' => 'bg-yellow-500',
            'hover' => 'hover:bg-yellow-600',
        ],
    ];

    $currentType = $types[$type] ?? $types['add'];
@endphp
<flux:button href="{!! $href !!}" icon="{{ $currentType['icon'] }}" size="sm"
    wire:click="{{ $click }}">{!! $title ? $title : $currentType['defaultTitle'] !!}
</flux:button>
