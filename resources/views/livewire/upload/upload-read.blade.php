 <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
     <x-manta.breadcrumb :$breadcrumb />
     <x-manta.buttons.large type="edit" :href="route('upload.update', ['upload' => $upload])" />
     <ul class="justify-left flex flex-wrap gap-4 p-4">
         <li class="flex w-1/6 flex-col items-center" draggable="true">
             @if (!$upload->getImage()['src'])
                 <!-- Font Awesome icon for file -->
                 <div class="flex aspect-square w-full items-center justify-center rounded-lg bg-gray-200 shadow-lg">
                     <i class="{{ $upload->getIcon() }} fa-5x text-gray-400"></i>
                 </div>
             @else
                 <!-- Image thumbnail -->
                 <div class="aspect-square w-full rounded-lg bg-cover bg-center shadow-lg"
                     style="background-image: url('{{ $upload->getImage()['src'] }}');">
                     <a href="{{ $upload->getImage()['src'] }}" class="flex h-full w-full items-center justify-center"
                         data-fancybox="gallery" data-caption="{{ $upload->title }}">
                         <!-- Transparent overlay (optional) -->
                     </a>
                 </div>
             @endif
     </ul>

     <div class="space-y-12 sm:space-y-16">
         <div
             class="mt-10 space-y-8 border-b border-gray-900/10 pb-12 sm:space-y-0 sm:divide-y sm:divide-gray-900/10 sm:border-t sm:pb-0">
             @foreach ($fields as $key => $value)
                 @if (isset($value['read']) && (!isset($value['active']) || $value['active'] == true))
                     <x-manta.forms.read title="{{ $value['title'] }}" name="{{ $key }}" :value="$upload->$key"
                         :type="isset($value['read_type']) ? $value['read_type'] : null" />
                 @endif
             @endforeach

         </div>
     </div>

 </div>
