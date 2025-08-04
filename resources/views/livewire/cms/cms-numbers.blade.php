<flux:main container>
    <x-manta.breadcrumb :$breadcrumb />
    <div class="row">
        <div class="col-6">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Locale</flux:table.column>
                    <flux:table.column>File</flux:table.column>
                    <flux:table.column>Aantal</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @foreach ($translations as $item)
                        <flux:table.row>
                            <flux:table.cell>{{ $item['locale'] }}</flux:table.cell>
                            <flux:table.cell>{{ $item['file'] }}</flux:table.cell>
                            <flux:table.cell>{{ $item['count'] }}</flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
                <flux:table.columns>
                    <flux:table.column></flux:table.column>
                    <flux:table.column></flux:table.column>
                    <flux:table.column>{{ $translations_total }}</flux:table.column>
                </flux:table.columns>
            </flux:table>
        </div>
        <div class="col-6">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Sleutel</flux:table.column>
                    <flux:table.column>Waarde</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    <flux:table.row>
                        <flux:table.cell>Routes</flux:table.cell>
                        <flux:table.cell>{{ $routes }}</flux:table.cell>
                    </flux:table.row>
                    <flux:table.row>
                        <flux:table.cell>Routes vertaald</flux:table.cell>
                        <flux:table.cell>{{ $routes_translated }}</flux:table.cell>
                    </flux:table.row>
                </flux:table.rows>
            </flux:table>
        </div>
    </div>
</flux:main>
