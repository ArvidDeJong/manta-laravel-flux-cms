   @if (env('OPENAI_API_KEY') && isset($fields['chatgpt']) && $fields['chatgpt']['active'] == true)
       <flux:modal.trigger name="chatgpt-modal">
           <flux:button icon="arrow-path" class="mb-8">Gebruik ChatGPT</flux:button>
       </flux:modal.trigger>

       <flux:modal name="chatgpt-modal" class="space-y-6 md:w-96">
           <div>
               <flux:heading size="lg">ChatGPT</flux:heading>
               <flux:subheading>Genereer bericht</flux:subheading>
           </div>

           <flux:input wire:model="openaiSubject" label="Onderwerp"
               description="Schrijf het onderwerp van je nieuwsbericht" />
           <flux:textarea rows="auto" wire:model="openaiDescription" label="Bericht"
               description="Omschrijf het bedrijf en zijn doelgroep" />

           <div class="flex">
               <flux:spacer />

               <flux:button icon="arrow-path" wire:click="getOpenai" class="mb-4 mt-4">Genereer bericht</flux:button>
           </div>
       </flux:modal>
   @endif
