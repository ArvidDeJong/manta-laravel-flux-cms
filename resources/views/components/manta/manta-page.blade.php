{!! translate($page)['result']->$attribute !!}
@if ($edit && auth('staff')->user())
    <p>
        <a href="{{ route('page.read', ['page' => $page]) }}" style="text-decoration: none; "><i
                class="fa-solid fa-pen-to-square"></i> Tekst</a>
    </p>
@endif
