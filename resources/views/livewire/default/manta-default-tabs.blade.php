  @if (isset($tablistModule) && count($tablistModule) > 1)
      <div class="mb-8 mt-8">
          <flux:tabs>
              @foreach ($tablistModule as $tab)
                  <flux:tab name="{{ $tab['name'] }}" href="{{ $tab['url'] }}">{{ $tab['title'] }}
                      @if (isset($tab['badge']))
                          <flux:badge>{{ $tab['badge'] }}</flux:badge>
                      @endif
                  </flux:tab>
              @endforeach
          </flux:tabs>
      </div>
  @endif
