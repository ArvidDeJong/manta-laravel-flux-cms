<div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
    <x-manta.breadcrumb :$breadcrumb />
    @include('manta-cms::livewire.default.manta-default-tabs', [
        'tabs' => $tablistModule,
        'tablistShow' => $tablistModuleShow,
    ])

    <div class="mt-8"></div>
    @if (!$item)
        <x-manta.alert type="warning" title="Het item bestaat nog niet" />
        <x-manta.buttons.large type="add" :href="route($this->module_routes['create'], ['pid' => $pid, 'locale' => $locale])" />
    @else
        <x-manta.buttons.large type="edit" :href="route($this->module_routes['update'], [$this->module_routes['name'] => $item])" />
        <p>&nbsp;</p>

        @foreach ($fields as $key => $value)
            @if (isset($value['read']) && $value['read'] == true && (!isset($value['active']) || $value['active'] == true))
                @php
                    $newvalue = isset($value['value']) ? $value['value'] : (isset($item->$key) ? $item->$key : null); // Controleer of $item->$key bestaat
                    if (is_array($newvalue)) {
                    } else {
                        if (
                            isset($value['options']) &&
                            is_array($value['options']) &&
                            isset($value['options'][$newvalue])
                        ) {
                            $newvalue = $value['options'][$newvalue]; // Controleer of de optie bestaat
                        }
                    }

                @endphp
                <x-manta.forms.read title="{{ $value['title'] }}" name="{{ $key }}" :value="$newvalue"
                    :type="isset($value['read_type']) ? $value['read_type'] : null" />
            @endif
        @endforeach

        {{-- @if ($item->locale == getLocaleManta())
            <livewire:manta-cms::livewire.upload.upload-overview :model_class="$item" :key="'overview' . time()" />
        @endif --}}
    @endif
</div>
