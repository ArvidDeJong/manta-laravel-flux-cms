<flux:main container>

    <x-manta.breadcrumb :$breadcrumb />
    @if ($model_class)
        <div class="mb-3 flex flex-wrap items-center justify-center">
            <div class="w-full max-w-md">
                <div class="mb-4 rounded bg-white px-8 pb-8 pt-6 shadow-md">
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

                        <flux:input type="file" wire:model="documents" label="Selecteer document(en) om te uploaden:"
                            multiple />
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

</flux:main>
