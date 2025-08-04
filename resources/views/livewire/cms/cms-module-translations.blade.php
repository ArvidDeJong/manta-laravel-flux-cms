<flux:main container>
    <x-manta.breadcrumb :$breadcrumb />

    <flux:heading size="xl">Basistaal: {{ getLocaleManta() }}</flux:heading>
    <flux:subheading>Er zijn in totaal <strong>{{ $total }}</strong> items</flux:subheading>

    <flux:table x-data="{ activeId: null }" class="mt-8">
        <flux:table.columns>
            <flux:table.column>Omschrijving</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            {{-- {!! dd($items) !!} --}}
            @foreach ($items as $key => $item)
                <flux:table.row>
                    <flux:table.cell style="width: 50px;">
                        @if ($item['total'] > 0)
                            <flux:button icon="eye" variant="subtle"
                                @click="activeId = activeId === '{{ $key }}' ? null : '{{ $key }}'" />
                        @endif
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:heading>

                            {{ $key }}
                            <flux:badge color="red">Nog &nbsp;<stong>
                                    {{ $item['to_translate'] . ' / ' . $item['total'] }}
                                </stong>&nbsp; te
                                vertalen
                            </flux:badge>

                        </flux:heading>
                    </flux:table.cell>
                </flux:table.row>
                @if ($item['total'] > 0)
                    @foreach ($item['rows'] as $key2 => $row)
                        @if (!isset($row['title']))
                            {!! dd($row) !!}
                        @endif
                        <flux:table.row id="row-{{ $key }}-{{ $key2 }}"
                            x-show="activeId === '{{ $key }}'">
                            <flux:table.cell>
                            </flux:table.cell>
                            <flux:table.cell>{{ $row['title'] }}</flux:table.cell>
                            @foreach (getLocalesManta() as $locale)
                                @if (isset($row[$locale['locale']]))
                                    <flux:table.cell>
                                        @if ($row[$locale['locale']]['variant'] == 'update')
                                            <flux:button href="{{ $row[$locale['locale']]['route'] }}">
                                                {{ $locale['locale'] }}
                                            </flux:button>
                                        @else
                                            <flux:button href="{{ $row[$locale['locale']]['route'] }}"
                                                variant="danger">
                                                {{ $locale['locale'] }}</flux:button>
                                        @endif
                                    </flux:table.cell>
                                @endif
                            @endforeach
                        </flux:table.row>
                    @endforeach
                @endif
            @endforeach
        </flux:table.rows>
    </flux:table>
</flux:main>
