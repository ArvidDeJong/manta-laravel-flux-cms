@if (isset($emailcodes) && count($emailcodes))
    <flux:modal.trigger name="emailcodes-modal">
        <flux:button class="mb-8" icon="bolt">Email codes</flux:button>
    </flux:modal.trigger>
    <flux:modal name="emailcodes-modal" class="space-y-6 md:w-96">
        <div>
            <flux:heading size="lg">Email codes</flux:heading>
            <flux:subheading>Deze codes vervangen de gegevens in de email</flux:subheading>
        </div>

        <flux:table>
            <flux:table.columns>
                <flux:table.column>Titel</flux:table.column>
                <flux:table.column>Code</flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @foreach ($emailcodes as $key => $value)
                    <flux:table.row>
                        <flux:table.cell>{{ $value['title'] }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:input value="{{ $value['value'] }}" readonly copyable />
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

        <div class="flex">
            <flux:spacer />

            <flux:button type="submit" variant="primary">Sluiten</flux:button>
        </div>
    </flux:modal>
@endif

<form wire:submit="putSettings" class="space-y-6">

    @foreach ($settingsArr as $key => $value)
        @if ($settings[$key]['type'] == 'text' || $settings[$key]['type'] == 'email' || $settings[$key]['type'] == 'password')
            <flux:field>
                @if (isset($settings[$key]['required']) && $settings[$key]['required'] == true)
                    <flux:label badge="Verplicht">{{ $settings[$key]['title'] }}</flux:label>
                @else
                    <flux:label>{{ $settings[$key]['title'] }}</flux:label>
                @endif
                @if ($settings[$key]['type'] == 'password')
                    <flux:input type="password" viewable wire:model="settingsArr.{{ $key }}">
                        <x-slot name="iconTrailing">
                            <flux:button size="sm" variant="subtle" icon="eye" class="-mr-1" />
                        </x-slot>
                    </flux:input>
                @else
                    <flux:input wire:model="settingsArr.{{ $key }}" type="{{ $settings[$key]['type'] }}" />
                @endif

                <flux:error name="{{ $key }}" />

                @if (isset($locale) && $locale != getLocaleManta() && $itemOrg)
                    <flux:description>Vertaling van: {{ $itemOrg->$key }}</flux:description>
                @endif
            </flux:field>
        @elseif ($settings[$key]['type'] == 'number')
            <flux:field>
                @if (isset($settings[$key]['required']) && $settings[$key]['required'] == true)
                    <flux:label badge="Verplicht">{{ $settings[$key]['title'] }}</flux:label>
                @else
                    <flux:label>{{ $settings[$key]['title'] }}</flux:label>
                @endif
                <flux:input wire:model="settingsArr.{{ $key }}" type="{{ $settings[$key]['type'] }}"
                    step="{{ isset($settings[$key]['step']) ? $settings[$key]['step'] : 1 }}" />
                <flux:error name="{{ $key }}" />
            </flux:field>
        @elseif ($settings[$key]['type'] == 'date')
            <flux:field>
                @if ($settings[$key]['required'])
                    <flux:label badge="Verplicht">{{ $settings[$key]['title'] }}</flux:label>
                @else
                    <flux:label>{{ $settings[$key]['title'] }}</flux:label>
                @endif
                <flux:input wire:model="settingsArr.{{ $key }}" type="{{ $settings[$key]['type'] }}" />
                <flux:error name="{{ $key }}" />
            </flux:field>
        @elseif ($settings[$key]['type'] == 'datetime-local')
            <flux:field>
                @if ($settings[$key]['required'])
                    <flux:label badge="Verplicht">{{ $settings[$key]['title'] }}</flux:label>
                @else
                    <flux:label>{{ $settings[$key]['title'] }}</flux:label>
                @endif
                <flux:input wire:model="settingsArr.{{ $key }}" type="{{ $settings[$key]['type'] }}" />
                <flux:error name="{{ $key }}" />
            </flux:field>
        @elseif ($settings[$key]['type'] == 'checkbox')
            <flux:checkbox wire:model="settingsArr.{{ $key }}" label="{{ $settings[$key]['title'] }}" />
        @elseif ($settings[$key]['type'] == 'checklist')
            <flux:field>
                <flux:checkbox.group label="{{ $settings[$key]['title'] }} ">
                    @foreach ($value['options'] as $key2 => $value2)
                        <flux:checkbox wire:model="settingsArr.{{ $key }}.{{ $key2 }}"
                            label="{{ $value2 }}" />
                    @endforeach
                </flux:checkbox.group>
            </flux:field>
        @elseif ($settings[$key]['type'] == 'locale')
            @php
                $locales = [];
                foreach (getLocalesManta() as $locale) {
                    $locales[$locale['locale']] = $locale['locale'];
                }
            @endphp
            <flux:field>
                <flux:label>{{ $settings[$key]['title'] }}</flux:label>
                <flux:select wire:model="settingsArr.{{ $key }}" size="sm"
                    placeholder="{{ $settings[$key]['title'] }}...">
                    @foreach ($locales as $key1 => $value1)
                        <flux:select.option value="{{ $key1 }}">{{ $value1 }}</flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>
        @elseif ($settings[$key]['type'] == 'select' && isset($value['options']))
            <flux:field>
                <flux:label>{{ $settings[$key]['title'] }}</flux:label>
                <flux:select variant="listbox" wire:model="settingsArr.{{ $key }}" size="sm"
                    placeholder="{{ $settings[$key]['title'] }}...">
                    @foreach ($value['options'] as $key1 => $value1)
                        <flux:select.option value="{{ $key1 }}">{{ $value1 }}</flux:select.option>
                    @endforeach

                </flux:select>
            </flux:field>
        @elseif ($settings[$key]['type'] == 'textarea')
            <flux:field>
                @if (isset($settings[$key]['required']) && $settings[$key]['required'])
                    <flux:label badge="Verplicht">{{ $settings[$key]['title'] }}</flux:label>
                @else
                    <flux:label>{{ $settings[$key]['title'] }}</flux:label>
                @endif
                <flux:textarea wire:model="settingsArr.{{ $key }}" rows="auto" />
                <flux:error name="{{ $key }}" />
            </flux:field>
        @elseif ($settings[$key]['type'] == 'editor')
            <flux:editor wire:model="settingsArr.{{ $key }}" label="{{ $settings[$key]['title'] }}" />
        @elseif ($settings[$key]['type'] == 'wysiwyg')
            <x-manta.forms.wysiwyg title="{{ $settings[$key]['title'] }}" name="settingsArr.{{ $key }}"
                :type="$settings[$key]['type']" />
        @elseif ($settings[$key]['type'] == 'tinymce-email')
            <x-manta.forms.wysiwyg title="{{ $settings[$key]['title'] }}" name="settingsArr.{{ $key }}"
                :type="$settings[$key]['type']" email />
        @elseif ($settings[$key]['type'] == 'email_receivers')
            <div class="grid grid-cols-2 gap-4">
                <flux:field>
                    <flux:label>{{ $settings[$key]['title'] }}</flux:label>
                    <flux:textarea wire:model.blur="settingsArr.{{ $key }}" rows="auto" />
                    <flux:error name="settingsArr.{{ $key }}" />
                    <flux:description>Ook versturen naar de zender? Gebruik: ##ZENDER##</flux:description>
                </flux:field>
                <ul class="pl-5 mt-8 list-disc">
                    @foreach (explode(PHP_EOL, $settingsArr[$key]) as $key => $value)
                        @if (!empty($value))
                            <li class="flex items-center">
                                {!! filter_var($value, FILTER_VALIDATE_EMAIL) || $value == '##ZENDER##'
                                    ? '<i class="mr-2 text-green-600 fa-solid fa-check"></i>'
                                    : '<i class="mr-2 text-red-600 fa-solid fa-xmark"></i>' !!} <flux:subheading>{{ $value }}</flux:subheading>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @elseif ($settings[$key]['type'] == 'divider')
            <flux:separator text="{{ $settings[$key]['title'] }}" />
        @endif
    @endforeach
    @include('manta-cms::includes.form_error')

    <flux:button type="submit" variant="primary">Opslaan</flux:button>
    <flux:button type="button" href="{{ $route_list }}">Terug naar lijst</flux:button>
</form>
