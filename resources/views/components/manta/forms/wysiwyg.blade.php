@props(['name', 'title', 'email' => false])
<flux:field wire:ignore>
    <flux:label>{{ $title }}</flux:label>
    <flux:textarea wire:model="{{ $name }}" id="{{ preg_replace('/[^a-zA-Z]/', '', $name) }}" />
    <flux:error name="{{ $name }}" />
</flux:field>

{{-- <div class="flex items-start py-6">
    <label for="{{ $name }}"
        class="block w-[150px] flex-none pt-1.5 text-sm font-medium leading-6 text-gray-900">{{ $title }}</label>
    <div class="flex-1 mt-2" wire:ignore>
        <textarea wire:model="{{ $name }}" id="{{ $name }}"
            class="block w-full rounded-md border-0 py-1.5 pb-1 pl-4 pr-2 pt-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
        @error($name)
            <span class="block mt-1 text-red-500">{{ $message }}</span>
        @enderror
    </div>
</div> --}}

@push('scripts')
    <script>
        tinymce.init({
            promotion: false,
            branding: false,
            //    path_absolute: '/',
            relative_urls: false, // Zorgt ervoor dat links absoluut blijven
            remove_script_host: true, // Behoudt het domein in de URL
            convert_urls: false, // Voorkomt dat TinyMCE de URL's herschrijft
            selector: '#{{ preg_replace('/[^a-zA-Z]/', '', $name) }}',
            height: 700,
            language: 'nl',
            // content_css : "",
            setup: function(editor) {
                editor.on('init change', function() {
                    editor.save();
                });
                editor.on('change', function(e) {
                    @this.set('{{ $name }}', editor.getContent());
                });
            },
            @if ($email)

                plugins: [
                    'table',
                    'importcss',
                    'searchreplace',
                    'code',
                    'visualchars',
                    'codesample',
                    'charmap',
                    'nonbreaking',
                    'anchor',
                    'insertdatetime',
                    'advlist',
                    'lists',
                    'wordcount',
                    'quickbars',

                ],
            @else

                content_css: tiny_css,
                plugins: [
                    'searchreplace',
                    'autolink',
                    'directionality',
                    'visualblocks',
                    'visualchars',
                    'fullscreen',
                    'image',
                    'link',
                    'media',
                    'codesample',
                    'table',
                    'charmap',
                    'pagebreak',
                    'nonbreaking',
                    'anchor',
                    'insertdatetime',
                    'advlist',
                    'lists',
                    'wordcount',
                    'help',
                    'code',



                ],
                menubar: 'edit insert view format table tools',
                // Toolbar nu als array, maar nog steeds dezelfde functies.
                toolbar: [
                    'formatselect | bold italic strikethrough forecolor backcolor   ' +
                    '| link image media pageembed ' +
                    '| alignleft aligncenter alignright alignjustify ' +
                    '| numlist bullist outdent indent ' +
                    '| removeformat ' +
                    '| addcomment'
                ],
                file_picker_callback: function(callback, value, meta) {
                    var x = window.innerWidth || document.documentElement.clientWidth || document
                        .getElementsByTagName('body')[0].clientWidth;
                    var y = window.innerHeight || document.documentElement.clientHeight || document
                        .getElementsByTagName('body')[0].clientHeight;

                    var cmsURL = '/laravel-filemanager?tinymce_version=6&editor=' + meta.fieldname;
                    if (meta.filetype == 'image') {
                        cmsURL = cmsURL + "&type=Images";
                    } else {
                        cmsURL = cmsURL + "&type=Files";
                    }

                    tinyMCE.activeEditor.windowManager.openUrl({
                        url: cmsURL,
                        title: 'Filemanager',
                        width: x * 0.8,
                        height: y * 0.8,
                        resizable: "yes",
                        close_previous: "no",
                        onMessage: (api, message) => {
                            callback(message.content);
                        }
                    });
                }
            @endif
        })
    </script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('set-tinymce-message', (event) => {
                if (tinymce.get(event[0].field)) {
                    console.log(event);
                    tinymce.get(event[0].field).setContent(event[0].content);
                }
            });
        });
    </script>
@endpush
