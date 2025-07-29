<div class="py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <x-manta.breadcrumb :$breadcrumb />

    @include('manta-cms::livewire.default.manta-default-tabs', [
        'tabs' => $tablistModule,
        'tablistShow' => $tablistModuleShow,
    ])

    <livewire:manta-cms::livewire.upload.upload-form :model_class="$item" />
    <livewire:manta-cms::livewire.upload.upload-overview :model_class="$item" :key="'overview' . time()" />
</div>
