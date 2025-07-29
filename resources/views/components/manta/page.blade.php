@props(['page' => null, 'attribute' => 'content', 'edit_button' => false])
@if ($page)
    {!! translate($page)['result']->$attribute !!}
    @if ($edit_button && auth('staff')->user())
        <p>
            <a href="{{ route('page.update', ['page' => $page]) }}"
                style="text-decoration: none; font-size: 12px; background-color: #fff;" target="_blank"><i
                    class="fa-solid fa-pen-to-square"></i> Tekst</a>
        </p>
    @endif
@endif
