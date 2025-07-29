<flux:main container>
    <x-manta.breadcrumb :$breadcrumb />

    @include('manta-cms::livewire.default.manta-default-tabs', [
        'tabs' => $tablistModule,
        'tablistShow' => $tablistShow,
    ])

    <div class="mt-8"></div>
    <div class="mt-4">
        <flux:heading size="lg">{{ __('manta-cms::messages.staff_right_title', ['name' => $item->name]) }}
        </flux:heading>
        <flux:subheading class="mt-2">{{ __('manta-cms::messages.staff_right_description') }}</flux:subheading>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
        @foreach ($availableRights as $category => $categoryData)
            <flux:card>
                <div class="flex items-center justify-between">
                    <div>
                        <flux:heading size="md">{{ $categoryData['label'] }}</flux:heading>
                        @if (!empty($categoryData['description']))
                            <flux:subheading class="mt-1">{{ $categoryData['description'] }}</flux:subheading>
                        @endif
                    </div>

                </div>

                <flux:checkbox.group>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        @foreach ($categoryData['rights'] as $right => $label)
                            <flux:checkbox :checked="$this->hasRight($category, $right)"
                                wire:model.live="rights.{{ $category }}.{{ $right }}"
                                :label="$label" />
                        @endforeach
                    </div>
                </flux:checkbox.group>
            </flux:card>
        @endforeach
    </div>

    <div class="mt-8 flex justify-end space-x-4">
        <flux:button variant="ghost" href="{{ route('manta-cms.staff.list') }}">
            {{ __('manta-cms::messages.cancel') }}
        </flux:button>
        <flux:button wire:click="save" variant="primary">
            {{ __('manta-cms::messages.save_rights') }}
        </flux:button>
    </div>
</flux:main>
