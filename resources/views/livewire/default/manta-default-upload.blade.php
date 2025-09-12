<flux:main container>
    <x-manta.breadcrumb :$breadcrumb />
    @include('manta-cms::livewire.default.manta-default-tabs', [
        'tabs' => $tablistModule,
        'tablistShow' => $tablistModuleShow,
    ])
    <livewire:manta-cms::livewire.upload.upload-form :model_class="$item" />
    <livewire:manta-cms::livewire.upload.upload-overview :model_class="$item" :key="'overview' . time()" />
</flux:main>
