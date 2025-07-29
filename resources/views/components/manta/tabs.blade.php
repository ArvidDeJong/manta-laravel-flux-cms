@props(['tablistShow', 'tablist'])
<flux:tabs class="mb-8" wire:model="tablistShow">
    @foreach ($tablist as $value)
        @if ($tablistShow == $value['tablistShow'])
            <flux:tab name="{!! $value['title'] !!}" style="background-color: lightgrey; color:black;">
                <a href="{{ $value['url'] }}">{!! $value['title'] !!}</a>
            </flux:tab>
        @else
            <flux:tab name="{!! $value['title'] !!}">
                <a href="{{ $value['url'] }}">{!! $value['title'] !!}</a>
            </flux:tab>
        @endif
    @endforeach
</flux:tabs>
