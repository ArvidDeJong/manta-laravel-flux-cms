<flux:main>

    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('manta-cms.dashboard') }}">Dashboard</flux:breadcrumbs.item>

        <flux:breadcrumbs.item href="{{ route('manta-cms.manta-module.list') }}">Modules</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>{{ $item->name }} ({{ $item->title }})</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <form wire:submit="save" class="mt-6">

        <flux:button type="submit" icon="check">Opslaan</flux:button>

        <flux:tab.group class="mt-6">
            <flux:tabs wire:model="show">
                <flux:tab name="active">Algemeen</flux:tab>
                <flux:tab name="fields">Velden</flux:tab>
                <flux:tab name="settings">Settings</flux:tab>
            </flux:tabs>
            <flux:tab.panel name="active">

                <div class="grid grid-cols-4 gap-x-6 gap-y-4">

                    <flux:input wire:model="title" label="Titel" />
                    <flux:input wire:model="tabtitle" label="Tabtitel" />
                    <flux:input wire:model="description" label="Beschrijving" />
                    <flux:input wire:model="route" label="Route" />
                    <flux:input wire:model="url" label="URL" />
                    <flux:input wire:model="icon" label="Icon" />

                    <flux:select wire:model="type" placeholder="Kies type..." label="Type">
                        <flux:select.option value="module">module</flux:select.option>
                        <flux:select.option value="webshop">webshop</flux:select.option>
                        <flux:select.option value="tool">tool</flux:select.option>
                        <flux:select.option value="dev">dev</flux:select.option>

                    </flux:select>

                    <flux:input wire:model="active" label="Actief" />
                    <flux:input wire:model="sort" label="Sortering" />

                </div>

            </flux:tab.panel>

            <flux:tab.panel name="fields">
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Titel</flux:table.column>
                        <flux:table.column>Actief</flux:table.column>
                        <flux:table.column>Type</flux:table.column>
                        <flux:table.column>Lezen</flux:table.column>
                        <flux:table.column>Verplicht</flux:table.column>
                        <flux:table.column>Edit</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @foreach ($fields as $key => $field)
                            <flux:table.row>
                                <flux:table.cell>{{ $field['title'] }}</flux:table.cell>
                                <flux:table.cell>
                                    <input wire:model="fields.{{ $key }}.active" type="checkbox" />
                                </flux:table.cell>
                                <flux:table.cell>{{ $field['type'] }}</flux:table.cell>
                                <flux:table.cell>
                                    <input wire:model="fields.{{ $key }}.read" type="checkbox" />
                                </flux:table.cell>
                                <flux:table.cell>
                                    <input wire:model="fields.{{ $key }}.required" type="checkbox" />
                                </flux:table.cell>
                                <flux:table.cell>
                                    <input wire:model="fields.{{ $key }}.edit" type="checkbox" />
                                </flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>
            </flux:tab.panel>
            <flux:tab.panel name="settings">
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Titel</flux:table.column>
                        <flux:table.column>Actief</flux:table.column>

                    </flux:table.columns>
                    @foreach ($settings as $key => $setting)
                        <flux:table.row>
                            <flux:table.cell>{{ $key }}</flux:table.cell>
                            <flux:table.cell>
                                @if (is_array($setting))
                                    @foreach ($setting as $key2 => $value2)
                                        <table width="100%">
                                            <tr>
                                                <td style="width: 50%">{{ $key2 }}</td>
                                                <td style="width: 50%">{{ $value2 }}</td>
                                            </tr>
                                        </table>
                                    @endforeach
                                @else
                                    {{ $setting }}
                                @endif

                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table>
            </flux:tab.panel>
        </flux:tab.group>

    </form>
</flux:main>
