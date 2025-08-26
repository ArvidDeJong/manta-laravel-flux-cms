<flux:main container>
    <x-manta.breadcrumb :$breadcrumb />
    @include('manta-cms::livewire.default.manta-default-openai-form')

    @include('manta-cms::includes.form_tabs', [
        'tabs' => $tablistModule,
        'tablistShow' => $tablistModuleShow,
    ])

    <div class="mt-8"></div>

    @include('manta-cms::includes.form_field_list')
</flux:main>
