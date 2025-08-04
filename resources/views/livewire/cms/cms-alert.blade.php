<div>
    @if ($visible)
        <div class="{{ $alertClasses }} relative flex items-center rounded border px-4 py-3" role="alert">
            <i class="fas {{ $icon }} mr-2"></i>
            <strong class="font-bold">{{ ucfirst($type) }}!</strong>
            <span class="ml-2 block sm:inline">{{ $message }}</span>
            @if ($remove)
                <button type="button" wire:click="closeAlert" class="absolute bottom-0 right-0 top-0 px-4 py-3"
                    aria-label="Close">
                    <svg class="h-6 w-6 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path
                            d="M14.348 14.849a1 1 0 01-1.414 0L10 11.414 7.066 14.35a1 1 0 11-1.414-1.415L8.586 10 5.652 7.066a1 1 0 111.414-1.414L10 8.586l2.934-2.934a1 1 0 111.414 1.414L11.414 10l2.934 2.934a1 1 0 010 1.415z" />
                    </svg>
                </button>
            @endif
        </div>
    @endif
</div>
