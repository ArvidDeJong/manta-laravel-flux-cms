 @props(['item' => null, 'image' => null])@if ($image != null)
     <div class="aspect-square w-32 rounded-lg bg-cover bg-center shadow-lg"
         style=" background-image: url('{{ $image }}');">
         <a href="{{ $image }}" class="flex h-full w-full items-center justify-center" data-fancybox="gallery"
             data-caption="">
             <!-- Transparent overlay (optional) -->
         </a>
     </div>
     <flux:avatar src="{{ $image }}" />
 @elseif ($item && $item->getImage()['src'])
     <div class="aspect-square w-32 rounded-lg bg-cover bg-center shadow-lg"
         style=" background-image: url('{{ $item->getImage()['src'] }}');">
         <a href="{{ $item->getImage()['src'] }}" class="flex h-full w-full items-center justify-center"
             data-fancybox="gallery" data-caption="{{ $item->title }}">
             <!-- Transparent overlay (optional) -->
         </a>
     </div>
 @elseif($item != null)
     <div class="flex aspect-square w-32 items-center justify-center rounded-lg bg-gray-200 shadow-lg">
         <i class="{{ $item->getIcon() }} fa-5x text-gray-400"></i>
     </div>
 @endif
