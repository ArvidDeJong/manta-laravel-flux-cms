<flux:main container>
    <x-manta.breadcrumb :$breadcrumb />
    <x-manta.buttons.large type="edit" :href="route($this->module_routes['update'], ['upload' => $item])" />
    <ul class="justify-left flex flex-wrap gap-4 p-4">
        <li class="flex w-1/6 flex-col items-center" draggable="true">
            @if (!$item->getImage()['src'])
                <!-- Font Awesome icon for file -->
                <div class="flex aspect-square w-full items-center justify-center rounded-lg bg-gray-200 shadow-lg">
                    <i class="{{ $upload->getIcon() }} fa-5x text-gray-400"></i>
                </div>
            @else
                <!-- Image thumbnail -->
                <div class="aspect-square w-full rounded-lg bg-cover bg-center shadow-lg"
                    style="background-image: url('{{ $item->getImage()['src'] }}');">
                    <a href="{{ $item->getImage()['src'] }}" class="flex h-full w-full items-center justify-center"
                        data-fancybox="gallery" data-caption="{{ $item->title }}">
                        <!-- Transparent overlay (optional) -->
                    </a>
                </div>
            @endif
    </ul>

    @if (auth('staff')->user()->developer)
        <div class="mt-6">
            <flux:heading>WebflowImage Component Code</flux:heading>
            <div class="mt-2 rounded-lg bg-gray-100 p-4">
                <pre class="whitespace-pre-wrap text-sm text-gray-800" id="component-code">&lt;x-manta.webflow-image 
    id="{{ $item->id }}"
    sizes="(max-width: 767px) 100vw, (max-width: 991px) 728px, 800px"
    class="img-large"
    alt="{{ $item->title ?? '' }}" /&gt;</pre>
                <flux:button size="sm" class="mt-2" onclick="copyToClipboard()">
                    Kopieer HTML
                </flux:button>
            </div>
        </div>

        <script>
            function copyToClipboard() {
                // Test alert direct
                alert('Test - functie aangeroepen');

                const code = document.getElementById('component-code').textContent;
                navigator.clipboard.writeText(code).then(function() {
                    // Toon success feedback
                    const button = event.target;
                    const originalText = button.textContent;
                    button.textContent = 'Gekopieerd!';
                    setTimeout(() => {
                        button.textContent = originalText;
                    }, 2000);

                    // Alert binnen Promise - mogelijk geblokkeerd
                    alert('Code gekopieerd naar klembord!');

                    // Toon Flux toast
                    Flux.toast('Code gekopieerd naar klembord!', {
                        duration: 2000,
                        variant: 'success'
                    });
                }).catch(function(err) {
                    //alert('Fout bij kopiëren: ' + err);
                });
            }
        </script>
    @endif

    <div class="space-y-12 sm:space-y-16">
        <div
            class="mt-10 space-y-8 border-b border-gray-900/10 pb-12 sm:space-y-0 sm:divide-y sm:divide-gray-900/10 sm:border-t sm:pb-0">
            @foreach ($fields as $key => $value)
                @if (isset($value['read']) && (!isset($value['active']) || $value['active'] == true))
                    <x-manta.forms.read title="{{ $value['title'] }}" name="{{ $key }}" :value="$item->$key"
                        :type="isset($value['read_type']) ? $value['read_type'] : null" />
                @endif
            @endforeach

        </div>
    </div>

</flux:main>
