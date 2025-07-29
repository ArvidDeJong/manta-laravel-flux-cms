<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <x-manta.breadcrumb :$breadcrumb />

    <div class="mt-5">
        <img src="{{ $this->image }}" id="image" class="w-80 h-96 mx-auto">



    </div>

    <div class="mt-5">
        <img id="cropped" src="" alt="" class="h-24 max-w-full ml-0">
    </div>

    <form wire:submit.prevent="store(Object.fromEntries(new FormData($event.target)))">
        <div class="mt-4">
            <div class="flex items-center">
                <input id="replace1" type="radio" wire:model="replace" name="replace" value="1" required>
                <label class="ml-2" for="replace1">Vervang huidige plaatje</label>
            </div>
            <div class="flex items-center mt-2">
                <input id="replace2" type="radio" wire:model="replace" name="replace" value="0">
                <label class="ml-2" for="replace2">Maak aan als nieuw plaatje</label>
            </div>
        </div>

        <div class="mt-4 flex items-center">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2"
                wire:loading.class="opacity-50" wire:loading.attr="disabled">
                <i class="fa-solid fa-floppy-disk"></i> Opslaan
            </button>
            <a href="javascript:;" id="download" download="plaaje.jpg"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <i class="fa-solid fa-download"></i> Download
            </a>
            <textarea name="newimage" id="newimage" class="hidden"></textarea>
        </div>
    </form>
</div>



@push('scripts')
    <script>
        const image = document.getElementById('image');
        cropper = new Cropper(image, {
            crop(event) {
                // Haal de originele afbeelding dimensies op
                const originalWidth = image.naturalWidth;
                const originalHeight = image.naturalHeight;

                // Bereken de schaal factor tussen de weergave en originele grootte
                const scaleX = originalWidth / event.detail.width;
                const scaleY = originalHeight / event.detail.height;

                // Maak een canvas met behoud van originele resolutie
                let imgSrc = cropper.getCroppedCanvas({
                    // Gebruik de werkelijke dimensies van het uitgesneden gebied
                    width: event.detail.width * scaleX,
                    height: event.detail.height * scaleY,
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high'
                }).toDataURL("image/jpeg", 1.0); // Maximale kwaliteit

                // Preview met kleinere afmeting voor performance
                const previewCanvas = cropper.getCroppedCanvas({
                    width: 200
                });
                document.getElementById("cropped").src = previewCanvas.toDataURL("image/jpeg", 0.8);

                // Originele hoge resolutie voor download en opslaan
                document.getElementById("download").href = imgSrc;
                document.getElementById("newimage").value = imgSrc;
            },
            viewMode: 2, // Beperk de view tot de afbeelding
            responsive: true,
            restore: true,
            checkCrossOrigin: false,
            checkOrientation: false, // Voorkom automatische rotatie
            modal: true
        });
    </script>
@endpush
