<flux:main container>
    <x-manta.breadcrumb :$breadcrumb />
    {{-- <x-manta.tabs :$tablist :$tablistShow /> --}}
    <div>
        <ul class="justify-left flex flex-wrap gap-4 p-4">
            @foreach ($thumbnails as $thumbnail)
                <li id="thumbnail-{{ $thumbnail['id'] }}" class="flex w-1/6 flex-col items-center" draggable="true">
                    @if (!$thumbnail['url'])
                        <!-- Font Awesome icon for file -->
                        <div
                            class="flex aspect-square w-full items-center justify-center rounded-lg bg-gray-200 shadow-lg">
                            <i class="{{ $thumbnail['icon'] }} fa-5x text-gray-400"></i>
                        </div>
                    @else
                        <!-- Image thumbnail -->
                        <div class="aspect-square w-full rounded-lg bg-cover bg-center shadow-lg"
                            style="background-image: url('{{ $thumbnail['url'] }}');">
                            <a href="{{ $thumbnail['url'] }}" class="flex h-full w-full items-center justify-center"
                                data-fancybox="gallery" data-caption="{{ $thumbnail['title'] }}">
                                <!-- Transparent overlay (optional) -->
                            </a>
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>

    @if (auth('staff')->user()->developer)
        <div class="grid w-full grid-cols-4 gap-4">
            <div>
                <flux:heading>Aangemaakt door</flux:heading>
            </div>
            <div>
                <flux:subheading>{{ $item->created_by }}</flux:subheading>
            </div>
            <div>
                <flux:heading>Aangemaakt op</flux:heading>
            </div>
            <div>
                <flux:subheading>{{ $item->created_at }}</flux:subheading>
            </div>
        </div>
        <div class="grid w-full grid-cols-4 gap-4">
            <div>
                <flux:heading>Laatst aangepast door</flux:heading>
            </div>
            <div>
                <flux:subheading>{{ $item->updated_by }}</flux:subheading>
            </div>
            <div>
                <flux:heading>Laatst aangepast op</flux:heading>
            </div>
            <div>
                <flux:subheading>{{ $item->updated_at }}</flux:subheading>
            </div>
        </div>
        <div class="grid w-full grid-cols-4 gap-4">
            <div>
                <flux:heading>Model</flux:heading>
            </div>
            <div>
                <flux:subheading>{{ $item->model }}</flux:subheading>
            </div>
            <div>
                <flux:heading>PID</flux:heading>
            </div>
            <div>
                <flux:subheading>{{ $item->pid }}</flux:subheading>
            </div>
        </div>
        <div class="grid w-full grid-cols-4 gap-4">
            <div>
                <flux:heading>Extension</flux:heading>
            </div>
            <div>
                <flux:subheading>{{ $item->extension }}</flux:subheading>
            </div>
            <div>
                <flux:heading>Grootte</flux:heading>
            </div>
            <div>
                <flux:subheading>{{ $item->size / 1024 }} KB</flux:subheading>
            </div>
        </div>
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
    @include('manta-cms::includes.form_field_list')

</flux:main>
