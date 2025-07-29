@props(['name', 'title', 'value', 'type' => null, 'options' => null])
<flux:field>
    <flux:label>{{ $title }}</flux:label>
    <flux:description>
        @if ($type == 'bool')
            {!! $value
                ? '<i class="text-green-600 fa-solid fa-check"></i>'
                : '<i class="text-red-600 fa-solid fa-xmark"></i>' !!}
        @elseif($type == 'datetime-local' && !empty($value))
            {{ Carbon\Carbon::parse($value)->format('d-m-Y H:i') }}
        @elseif($type == 'date' && !empty($value))
            {{ Carbon\Carbon::parse($value)->format('d-m-Y') }}
        @elseif($type == 'select' && is_array($options) && !empty($value) && isset($options[$value]))
            {{ $options[$value] }}
        @else
            @if (is_array($value))

                @foreach ($value as $key => $row)
                    <flux:subheading> - {{ $row }}</flux:subheading>
                @endforeach
            @elseif($type == 'wysiwyg')
                {!! $value !!}
            @else
                {!! nl2br($value) !!}
            @endif
        @endif
    </flux:description>
</flux:field>
