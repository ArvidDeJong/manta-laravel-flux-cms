<flux:main container>
    <x-manta.breadcrumb :$breadcrumb />
    @include('manta-cms::livewire.default.manta-default-openai-form')

    @include('manta-cms::includes.form_tabs', [
        'tabs' => $tablistModule,
        'tablistShow' => $tablistModuleShow,
    ])

    <div class="pt-8">
        @include('manta-cms::includes.form_field_list')
    </div>
</flux:main>
