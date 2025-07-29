<div>
    @if (count($thumbnails) < 1 && $model_class)
        <x-manta.alert type="info" title="Er zijn nog geen bestanden aanwezig" />
    @endif

    <div>
        <ul class="flex flex-wrap justify-center gap-4 p-4" id="draggable">
            @foreach ($thumbnails as $thumbnail)
                <li id="thumbnail-{{ $thumbnail['id'] }}" class="flex w-1/6 flex-col items-center"
                    data-id="{{ $thumbnail['id'] }}">
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
                    <div class="mt-2 flex w-full items-center justify-center space-x-2">

                        @if ($deleteId && $deleteId == $thumbnail['id'])
                            <button
                                class="mb-2 mr-2 rounded-lg bg-green-500 px-4 py-0.5 text-sm font-medium text-white hover:bg-green-600 focus:ring-4 focus:ring-green-300"
                                wire:click="deleteConfirm('{{ $thumbnail['id'] }}')">Verwijder</button>
                            <button
                                class="mb-2 rounded-lg bg-red-500 px-4 py-0.5 text-sm font-medium text-white hover:bg-red-600 focus:ring-4 focus:ring-red-300"
                                wire:click="deleteCancel('{{ $thumbnail['id'] }}')">Annuleer</button>
                        @else
                            <span class="handle"><i class="fa-solid fa-arrows-up-down-left-right"></i></span>
                            <a href="{{ $thumbnail['url_update'] }}" class="text-gray-600 hover:text-gray-800"><i
                                    class="fa fa-edit"></i></a>
                            @if ($thumbnail['image'])
                                <a href="{{ $thumbnail['url_crop'] }}" class="text-gray-600 hover:text-gray-800"><i
                                        class="fa fa-crop"></i></a>
                                <a href="javascript:;" wire:click="setmain('{{ $thumbnail['id'] }}')"
                                    class="{{ $thumbnail['main'] ? ' text-green-600 hover:text-green-800' : ' text-gray-600 hover:text-gray-800' }}"><i
                                        class="fa fa-house"></i></a>
                            @endif
                            {{-- <a href="{{ $thumbnail['url_rename'] }}" class="text-gray-600 hover:text-gray-800"><i
                                    class="fa fa-font"></i></a>  --}}
                            <a href="javascript:;" wire:click="delete('{{ $thumbnail['id'] }}')"
                                class="text-gray-600 hover:text-gray-800"><i class="fa fa-trash"></i></a>
                        @endif
                    </div>
                    <div class="mt-2 flex w-full items-center justify-center space-x-4">
                        <flux:subheading> {{ substr($thumbnail['title'], 0, 15) }}<br>
                            <em> {{ substr($thumbnail['identifier'], 0, 15) }}</em>
                        </flux:subheading>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
    <x-manta.draggable />

</div>
