@props(['name', 'title'])

<div class="sm:grid sm:grid-cols-6 sm:items-start sm:gap-4 sm:py-6">
    <label for="{{ $name }}"
        class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5 sm:col-span-1">{{ $title }}</label>
    <div class="mt-2 sm:col-span-3 sm:mt-0">
        <input type="date" wire:model="{{ $name }}" id="{{ $name }}"
            class="block w-full rounded-md border-0 py-1.5 pl-4 pr-2 pt-2 pb-1 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
        @error($name)
            <span class="text-red-500 mt-1 block">{{ $message }}</span>
        @enderror
    </div>
</div>
