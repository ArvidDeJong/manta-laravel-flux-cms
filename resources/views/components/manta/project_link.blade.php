@props(['slug' => null, 'id' => null])
@php
    $item = Manta\Models\Project::find($id);
    echo route('website.project', ['slug' => $item->slug]);
@endphp
