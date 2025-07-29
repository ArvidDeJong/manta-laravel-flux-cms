@props(['name', 'title'])

<div class="sm:grid sm:grid-cols-6 sm:items-start sm:gap-4 sm:py-6">
    <label for="{{ $name }}"
        class="block text-sm font-medium leading-6 text-gray-900 sm:col-span-1 sm:pt-1.5">{{ $title }}</label>
    <div class="mt-2 sm:col-span-3 sm:mt-0">
        <div class="flex items-center">
            <input type="checkbox" id="{{ $name }}" wire:model.live="{{ $name }}" value="1"
                class="text-indigo-600 border-gray-300 rounded shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <label for="{{ $name }}" class="block ml-2 text-sm text-gray-900"></label>
        </div>
        @error($name)
            <span class="block mt-1 text-red-500">{{ $message }}</span>
        @enderror
    </div>
</div>
