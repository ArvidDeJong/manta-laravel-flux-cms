@props(['title', 'column', 'sortCol', 'sortAsc'])

<button wire:click="sortBy('{{ $column }}')"
    {{ $attributes->merge(['class' => 'flex items-center gap-2 group']) }}>
    <div class="whitespace-nowrap">{{ $title }}</div>

    @if ($sortCol === $column)
        <div class="text-gray-400">
            @if ($sortAsc)
                <x-livewire.icon.arrow-long-up />
            @else
                <x-livewire.icon.arrow-long-down />
            @endif
        </div>
    @else
        <div class="text-gray-400 opacity-0 group-hover:opacity-100">
            <x-livewire.icon.arrows-up-down />
        </div>
    @endif
</button>
