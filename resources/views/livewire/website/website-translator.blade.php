<span>
    @auth('staff')
        <span x-data="{ focus: false }" wire:click="$set('editing', true)" style="width: 100%;">
            @if ($editing)
                <textarea wire:model="content" wire:blur="save" x-ref="editor" x-init="setTimeout(() => { $refs.editor.focus(); }, 50)"
                    style=" field-sizing: content; width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; outline: none; focus:border: blue; color:black; {{ $style }}">
                </textarea>
            @else
                <span wire:click="edit(); focus = true" style="{{ $style }}">
                    {!! $content !!}
                </span>
            @endif
        </span>
    @else
        {!! $content !!}
    @endauth
</span>
