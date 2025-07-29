@props(['name', 'title', 'options' => []])

<flux:field>

    <flux:checkbox.group wire:model="{{ $name }}" label="{{ $title }}">
        @foreach ($options as $key => $value)
            <flux:checkbox label="{{ $value }}" value="{{ $name }}.{{ $key }}" />

            {{-- <div class="flex items-center">
                <input type="checkbox" id="{{ $name }}.{{ $key }}"
                    wire:model="{{ $name }}.{{ $key }}" value="{{ $value }}"
                    class="text-indigo-600 border-gray-300 rounded shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <label for="{{ $name }}.{{ $key }}"
                    class="block ml-2 text-sm text-gray-900">{{ $value }}</label>
            </div> --}}
        @endforeach
    </flux:checkbox.group>
    @error($name)
        <span class="mt-1 block text-red-500">{{ $message }}</span>
    @enderror

</flux:field>
