@props(['item' => null, 'size' => null, 'noimage' => null, 'type' => null])@php
$theme = config('app.theme'); // Gebruik config() i.p.v. env()
$defaultImagePaths = ["/theme/{$theme}/manta/img/no-image.png", "/theme/{$theme}/manta/img/no-image.jpg"];

if (is_null($noimage)) {
foreach ($defaultImagePaths as $url) {
if (Illuminate\Support\Facades\File::exists(public_path($url))) {
$noimage = $url;
break;
}
}
}

if ($type == 'url'){
echo $item ? '/storage/'.$item->location.$item->filename : $noimage;
} else {
echo $item ? optional($item->getImage($size))['src'] : $noimage;
}

@endphp
