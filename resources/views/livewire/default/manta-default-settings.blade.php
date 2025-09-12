<flux:main container>
    <x-manta.breadcrumb :$breadcrumb />
    @include('manta-cms::livewire.default.manta-default-tabs', [
        'tabs' => $tablistModule,
        'tablistShow' => $tablistModuleShow,
    ])
    @include('manta-cms::includes.form_field_list-settings')
</flux:main>
