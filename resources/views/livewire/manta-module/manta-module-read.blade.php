<flux:main>

    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('manta-cms.dashboard') }}">Dashboard</flux:breadcrumbs.item>

        <flux:breadcrumbs.item href="{{ route('manta-cms.manta-module.list') }}">Modules</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>{{ $item->title }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>
    <div class="mt-6">
        <flux:button href="{{ route('manta-cms.manta-module.update', $item) }}" icon="pencil">Bewerken</flux:button>
    </div>
    <flux:tab.group class="mt-6">
        <flux:tabs wire:model="show">
            <flux:tab name="active">Algemeen</flux:tab>
            <flux:tab name="fields">Velden</flux:tab>
            <flux:tab name="settings">Settings</flux:tab>
        </flux:tabs>
        <flux:tab.panel name="active">

            <div class="grid grid-cols-4 gap-x-6 gap-y-4">
                <div>
                    <flux:heading size="lg">Naam</flux:heading>
                </div>
                <div>
                    <flux:text class="mt-2">{{ $name }}</flux:text>
                </div>
                <div>
                    <flux:heading size="lg">Titel</flux:heading>
                </div>
                <div>
                    <flux:text class="mt-2">{{ $title }}</flux:text>
                </div>

                <div>
                    <flux:heading size="lg">Tabtitel</flux:heading>
                </div>
                <div>
                    <flux:text class="mt-2">{{ $tabtitle }}</flux:text>
                </div>
                <div>
                    <flux:heading size="lg">Beschrijving</flux:heading>
                </div>
                <div>
                    <flux:text class="mt-2">{{ $description }}</flux:text>
                </div>

                <div>
                    <flux:heading size="lg">Route</flux:heading>
                </div>
                <div>
                    <flux:text class="mt-2">{{ $route }}</flux:text>
                </div>
                <div>
                    <flux:heading size="lg">URL</flux:heading>
                </div>
                <div>
                    <flux:text class="mt-2">{{ $url }}</flux:text>
                </div>

                <div>
                    <flux:heading size="lg">Icon</flux:heading>
                </div>
                <div>
                    <flux:text class="mt-2">{{ $icon }}</flux:text>
                </div>
                <div>
                    <flux:heading size="lg">Type</flux:heading>
                </div>
                <div>
                    <flux:text class="mt-2">{{ $type }}</flux:text>
                </div>

                <div>
                    <flux:heading size="lg">Actief</flux:heading>
                </div>
                <div>
                    <flux:text class="mt-2">{{ $active ? 'Ja' : 'Nee' }}</flux:text>
                </div>
                <div>
                    <flux:heading size="lg">Sortering</flux:heading>
                </div>
                <div>
                    <flux:text class="mt-2">{{ $sort }}</flux:text>
                </div>
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
                    @foreach ($fields as $field)
                        <flux:table.row>
                            <flux:table.cell>{{ $field['title'] }}</flux:table.cell>
                            <flux:table.cell>{{ isset($field['active']) && $field['active'] == true ? 'Ja' : 'Nee' }}
                            </flux:table.cell>
                            <flux:table.cell>{{ $field['type'] }}</flux:table.cell>
                            <flux:table.cell>{{ isset($field['read']) && $field['read'] == true ? 'Ja' : 'Nee' }}
                            </flux:table.cell>
                            <flux:table.cell>
                                {{ isset($field['required']) && $field['required'] == true ? 'Ja' : 'Nee' }}
                            </flux:table.cell>
                            <flux:table.cell>
                                {{ isset($field['edit']) && $field['edit'] == true ? 'Ja' : 'Nee' }}
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
</flux:main>
