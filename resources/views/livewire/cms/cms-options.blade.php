<flux:main container>
    <x-manta.breadcrumb :$breadcrumb />
    <flux:heading size="xl">Instellingen</flux:heading>
    <flux:subheading size="xl" class="mb-8">Hier staan de basiswaarden van de website</flux:subheading>
    @include('manta.includes.form_field_list')
    <div class="row mb-3 mt-4" wire:ignore>
        <label for="map" class="col-sm-2 col-form-label fw-bolder"></label>
        <div class="col-sm-6">
            <div id="map" style="width: 100%; height: 400px;"></div>
        </div>
    </div>
    @if ($maps_update)
        whoop
        <script>
            setNewCenter({{ $DEFAULT_LATITUDE }}, {{ $DEFAULT_LONGITUDE }});
        </script>
    @endif
    @push('scripts')
        @include('manta.includes.google_maps_js_load')
        @include('manta.includes.google_maps_js_set')
    @endpush
</flux:main>
