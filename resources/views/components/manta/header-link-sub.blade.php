@props([
    'href' => '/',
    'title' => null,
    'location' => null,
])
@php
    if ($location == 'top') {
        $class = 'rounded-t bg-gray-200 hover:bg-gray-400 py-2 px-4 block whitespace-no-wrap';
    } elseif ($location == 'bottom') {
        $class = 'rounded-b bg-gray-200 hover:bg-gray-400 py-2 px-4 block whitespace-no-wrap';
    } elseif ($location == 'single') {
        $class = 'rounded bg-gray-200 hover:bg-gray-400 py-2 px-4 block whitespace-no-wrap';
    } else {
        $class = 'bg-gray-200 hover:bg-gray-400 py-2 px-4 block whitespace-no-wrap';
    }
@endphp
<a class="{{ $class }}" href="{{ $href }}">{{ $title }}</a>
