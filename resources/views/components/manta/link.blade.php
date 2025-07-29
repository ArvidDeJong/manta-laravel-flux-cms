@props(['id' => null])
@php
    $page = Darvis\MantaPage\Models\Page::find($id);
    if ($page) {
        echo route('website.page', ['slug' => $page->slug]);
    }
@endphp
