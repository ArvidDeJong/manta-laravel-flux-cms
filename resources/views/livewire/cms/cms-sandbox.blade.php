<flux:main container>
    <x-manta.breadcrumb :$breadcrumb />
    <flux:heading size="xl" class="mb-8">Welkom in de zandbak</flux:heading>
    <flux:button x-on:click="$flux.dark = ! $flux.dark" icon="moon" variant="subtle" aria-label="Toggle dark mode" />
    @foreach ($result as $value)
        <pre>
{!! print_r($value['ResultData'][0]['cprs']) !!}
         </pre>
    @endforeach
</flux:main>
