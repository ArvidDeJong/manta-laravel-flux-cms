@if (!empty($src))
    <img sizes="{{ $sizes }}" @if ($srcset) srcset="{{ $srcset }}" @endif
        alt="{{ $alt }}" title="{{ $alt }}" src="{{ $src }}"
        @if ($class) class="{{ $class }}" @endif {{ $attributes }}>

    @if (auth('staff')->user() && isset($upload) && $upload && $showedit)
        <a href="{{ route('manta-cms.upload.update', $upload) }}"
            style="text-decoration: none; color: red; margin-top: -60px; margin-left: 10px">-EDIT-</a>
    @endif
@endif
