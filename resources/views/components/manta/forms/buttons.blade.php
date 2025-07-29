@props(['href' => null])
<div class="mt-6 flex items-center gap-x-6">
    <button type="submit" wire:loading.attr="disabled"
        class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 disabled:opacity-50">
        <span wire:loading.remove wire:target="save">
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </span>
        <span wire:loading wire:target="save">
            <svg class="mr-2 h-4 w-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 0v4m0-4h4m-4 0H8">
                </path>
            </svg>
        </span>
        Opslaan
    </button>
    @if ($href)
        <a href="{{ $href }}"
            class="text-sm font-semibold leading-6 text-gray-900 px-3 py-2 rounded-md bg-gray-100 hover:bg-gray-200">Annuleren</a>
    @endif
    {{ $slot }}
</div>
