   @if (env('OPENAI_API_KEY') && isset($fields['chatgpt']) && $fields['chatgpt']['active'] == true)
       <flux:modal.trigger name="chatgpt-modal">
           <flux:button icon="arrow-path">Gebruik ChatGPT</flux:button>
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
       @if (Manta\FluxCMS\Models\Upload::where('model_id', 'openai')->get()->count() > 0)
           <flux:callout icon="check-circle" variant="success">
               <flux:callout.heading>ChatGPT Afbeeldingen</flux:callout.heading>
               <flux:callout.text>
                   <div class="flex flex-wrap">
                       @foreach (Manta\FluxCMS\Models\Upload::where('model_id', 'openai')->get() as $upload)
                           <img src="{{ $upload->getImage()['src'] }}" alt="{{ $upload->title }}" height="100"
                               style="object-fit: cover; height: 100px; margin-left: 10px;" data-fancybox="gallery"
                               data-caption="">
                       @endforeach
                   </div>

                   <flux:field variant="inline" class="mt-4">
                       <flux:checkbox wire:model="openaiImageAdd" />
                       <flux:label>Voeg toe aan bericht</flux:label>
                       <flux:error name="openaiImageAdd" />
                   </flux:field>

               </flux:callout.text>
           </flux:callout>
       @endif
   @endif
