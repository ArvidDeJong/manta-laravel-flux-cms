  @if (isset($tablistModule) && count($tablistModule) > 1)
      <flux:tabs wire:model.live="tablistModuleShow" class="mb-8 mt-8">
          @foreach ($tablistModule as $tab)
              <flux:tab name="{{ $tab['name'] }}" href="{{ $tab['url'] }}" label="{{ $tab['title'] }}">
                  @if (isset($tab['badge']))
                      <flux:badge>{{ $tab['badge'] }}</flux:badge>
                  @endif
              </flux:tab>
          @endforeach
      </flux:tabs>
  @endif
