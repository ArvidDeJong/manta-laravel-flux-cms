@props(['breadcrumb', 'breadcrumb_show' => true])
@if ($breadcrumb_show && count($breadcrumb) > 0)
    <flux:breadcrumbs class="mb-8">
        @foreach ($breadcrumb as $key => $value)
            <flux:breadcrumbs.item href="{{ $value['url'] ?? '#' }}">
                @if (isset($value['flag']))
                    <i class="fi {{ $value['flag'] }} mx-1"></i>
                @endif

                {!! $value['title'] !!}

            </flux:breadcrumbs.item>
        @endforeach
    </flux:breadcrumbs>
@endif
