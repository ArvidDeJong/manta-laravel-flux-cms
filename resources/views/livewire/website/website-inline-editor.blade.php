<span>
    @auth('staff')
        @if ($wysiwyg)
            <span wire:ignore>
                <div id="editor{{ $id . $attribute }}" contenteditable="true" style="width: 100%;   {{ $style }}">
                    {!! $content !!}
                </div>
                @if ($route_edit && auth('staff')->user())
                    <p>
                        <a href="{{ $route_edit }}" style="text-decoration: none; font-size: 12px; background-color: #fff;"
                            target="_blank">
                            <i class="fa-solid fa-pen-to-square"></i> Aanpassen
                        </a>
                    </p>
                @endif
            </span>
        @else
            <span x-data="{ focus: false }" wire:click="$set('editing', true)" style="width: 100%;">
                @if ($editing)
                    <textarea wire:model="content" wire:blur="save" x-ref="editor" x-init="setTimeout(() => { $refs.editor.focus(); }, 50)"
                        style="width: 100%; min-height: 100px; min-width: 800px; padding: 8px; border: 1px solid #ccc; border-radius: 4px; outline: none; focus:border: blue; color:black; {{ $style }}">
                    </textarea>
                @else
                    <span wire:click="$wire.edit(); focus = true" style="{{ $style }}">
                        {{ $content }}
                    </span>
                @endif
            </span>
        @endif

        @push('styles')
            <script src="/libs/tinymce-7.6.0/js/tinymce/tinymce.min.js"></script>
            <script type="text/javascript" src="/manta/js/passive-events-tinymce.js"></script>

            <script>
                document.addEventListener("livewire:init", function() {
                    tinymce.init({
                        selector: '#editor{{ $id . $attribute }}',
                        inline: true,
                        menubar: false,
                        setup: function(editor) {
                            editor.on('change', function() {
                                @this.set('content', editor.getContent());
                            });
                        }
                    });
                });

                var tiny_css = '{{ env('TINY_CSS') }}';
            </script>
        @endpush
    @else
        {!! $content !!}
    @endauth
</span>
