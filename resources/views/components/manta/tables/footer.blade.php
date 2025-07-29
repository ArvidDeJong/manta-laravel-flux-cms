  @props(['items'])
  @if (count($items) == 0)
      <x-manta.alert type="info" title="Er is nog geen data beschikbaar" />
  @endif
  <div class="flex items-center justify-between pt-4">
      <div class="text-sm text-gray-700">
          Totaal:
          {{ \Illuminate\Support\Number::format($items->total()) }}
      </div>
      {{ $items->links() }}
  </div>

  {{-- <flux:table :paginate="$items">
      <!-- ... -->
  </flux:table> --}}
