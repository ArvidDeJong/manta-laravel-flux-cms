  @if (isset($tablistModule) && count($tablistModule) > 1)
      <flux:tabs class="mb-4 px-4" wire:model="tablistModuleShow">
          @foreach ($tablistModule as $tab)
              <flux:tab name="{{ $tab['name'] }}"><a href="{{ $tab['url'] }}">{{ $tab['title'] }}</a>
                  @if (isset($tab['badge']))
                      <flux:badge>{{ $tab['badge'] }}</flux:badge>
                  @endif
              </flux:tab>
          @endforeach
      </flux:tabs>
  @endif
