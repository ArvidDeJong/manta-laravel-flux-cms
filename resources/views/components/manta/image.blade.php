@props(['item' => null, 'size' => null, 'noimage' => null])@php
if (!$item) {
echo $noimage;
return;
}

if (!$noimage) {
$theme = env('THEME');
$paths = ["/theme/{$theme}/manta/img/no-image.png", "/theme/{$theme}/manta/img/no-image.jpg"];
foreach ($paths as $path) {
if (Illuminate\Support\Facades\File::exists(public_path($path))) {
$noimage = $path;
break;
}
}
}

echo $item->getImage()['src'] ?? $noimage;
@endphp
