<div class="container mx-auto mt-4">

    @if ($model_class)
        <div class="flex flex-wrap items-center justify-center mb-3">
            <div class="w-full max-w-md">
                <div class="px-8 pt-6 pb-8 shadow-md mb-4rounded">
                    <div class="mb-4">
                        {{-- <label class="block mb-2 text-sm font-bold text-gray-700" for="documents">
                            Selecteer document(en) om te uploaden:
                        </label>
                        <div>
                            <input type="file" wire:model.live="documents" multiple>
                            @error('documents.*')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div> --}}
                        <flux:input type="file" wire:model.live="documents" multiple
                            label="Selecteer document(en) om te uploaden:" description="Maximaal 5 MB per document." />
                    </div>
                    <div class="flex items-center justify-between">
                        {{-- <button wire:loading.attr="disabled" type="button" wire:click="save"
                            class="px-4 py-2 text-sm font-bold text-white bg-green-500 rounded-sm hover:bg-green-600 focus:outline-none focus:shadow-outline">
                            <i class="fa-solid fa-spinner fa-spin" wire:loading></i> Uploaden
                        </button> --}}
                        <div wire:loading class="text-gray-500">
                            <i class="fa-solid fa-spinner fa-spin"></i> Aan het uploaden...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
