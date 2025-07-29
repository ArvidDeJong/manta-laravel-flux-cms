<ul class="space-y-2">
    @foreach ($menuItems as $menuItem)
        <li>
            <label class="inline-flex items-center">
                <input type="checkbox" wire:model="selectedItems" value="{{ $menuItem['key'] }}"
                    class="form-checkbox text-blue-500">
                <span class="ml-2">{{ $menuItem['title'] }}</span>
            </label>
            @if (isset($menuItem['children']) && !empty($menuItem['children']))
                <ul class="ml-4 space-y-2">
                    @include('manta.includes.tree-component', ['menuItems' => $menuItem['children']])
                </ul>
            @endif
        </li>
    @endforeach
</ul>
