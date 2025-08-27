   @if (env('OPENAI_API_KEY') && isset($fields['chatgpt']) && $fields['chatgpt']['active'] == true)
       <flux:modal.trigger name="chatgpt-modal">
           <flux:button icon="arrow-path" class="mb-8">Gebruik ChatGPT</flux:button>
       </flux:modal.trigger>

       <flux:modal name="chatgpt-modal" class="space-y-6 md:w-96">
           <div>
               <flux:heading size="lg">ChatGPT</flux:heading>
               <flux:subheading>Genereer bericht</flux:subheading>
           </div>

           <flux:textarea wire:model="openaiSubject" label="Onderwerp" rows="auto"
               description="Schrijf het onderwerp van je bericht" />
           <flux:textarea rows="auto" wire:model="openaiDescription" label="Bericht"
               description="Omschrijf de details van je bericht" />
           @if ($openaiImagePossible)
               <flux:checkbox wire:model="openaiImageGenerate" label="Afbeelding genereren" />
           @endif

           <div class="flex">
               <flux:spacer />

               <flux:button icon="arrow-path" wire:click="getOpenaiResult" class="mb-4 mt-4">Genereer bericht
               </flux:button>
           </div>
       </flux:modal>

       @if ($openaiResult)
           <flux:callout icon="check-circle" variant="success">
               <flux:callout.heading>SEO Content Gegenereerd</flux:callout.heading>

               <flux:callout.text>
                   {!! $openaiResult !!}

                   {{-- @if (!$openaiImage && $openaiImagePrompt)
                      <div class="mt-4">
                          <flux:button icon="photo" wire:click="generateOpenaiImage" variant="outline" size="sm">
                              Genereer afbeelding
                          </flux:button>
                      </div>
                  @endif
                  
                  @if ($openaiImage)
                      <img src="{{ $openaiImage }}" alt="Generated Image" class="mt-4 h-auto max-w-full rounded">
                  @endif --}}
               </flux:callout.text>
           </flux:callout>
       @endif

       <div class="flex flex-wrap">
           @foreach ($uploads as $upload)
               <img src="{{ $upload->getImage()['src'] }}" alt="{{ $upload->title }}" height="100"
                   style="object-fit: cover; height: 100px; margin-left: 10px;" data-fancybox="gallery" data-caption="">
           @endforeach
       </div>
   @endif
