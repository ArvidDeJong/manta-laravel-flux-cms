@props([
    'src' => '',
    'alt' => '',
    'class' => '',
    'sizes' => '(max-width: 2000px) 100vw, 2000px',
    'srcset' => '',
    'breakpoints' => [500, 800, 1080, 1600, 2000],
    'basePath' => '',
    'extension' => 'jpg',
    'upload' => null,
])

@php
    // Auto-generate srcset if not provided but basePath is given
    if (empty($srcset) && !empty($basePath)) {
        $srcsetParts = [];
        $basePathWithoutExt = pathinfo($basePath, PATHINFO_DIRNAME) . '/' . pathinfo($basePath, PATHINFO_FILENAME);

        foreach ($breakpoints as $width) {
            if ($width === max($breakpoints)) {
                // Largest size uses original filename
                $srcsetParts[] = "{$basePath} {$width}w";
            } else {
                // Smaller sizes use -p-{width} suffix (Webflow convention)
                $srcsetParts[] = "{$basePathWithoutExt}-p-{$width}.{$extension} {$width}w";
            }
        }

        $srcset = implode(', ', $srcsetParts);
    }

    // Use basePath as src if src is empty
    if (empty($src) && !empty($basePath)) {
        $src = $basePath;
    }

    // Don't render img tag if no src is available
    $shouldRender = !empty($src);
@endphp

@if ($shouldRender)
    <img sizes="{{ $sizes }}" @if ($srcset) srcset="{{ $srcset }}" @endif
        alt="{{ $alt }}" title="{{ $alt }}" src="{{ $src }}"
        @if ($class) class="{{ $class }}" @endif {{ $attributes }}>

    @if (auth('staff')->user() && $upload)
        <a href="{{ route('manta-cms.upload.update', $upload) }}"
            style="text-decoration: none; color: red; margin-top: -60px; margin-left: 10px">-EDIT-</a>
    @endif
@endif
