@props([
    'item',
    'route_name',
    'maps' => false,
    'uploads' => false,
    'fields' => [],
    'moduleClass',
    'translatable' => true,
])
<flux:table.cell align="right">
    <flux:dropdown align="end" offset="-15">
        <flux:button icon="ellipsis-horizontal" size="sm" variant="ghost" inset="top bottom" />
        <flux:menu class="min-w-32">
            @if ($item->trashed())
                <flux:menu.item wire:click="restore" icon="arrow-path" variant="danger">Herstellen
                </flux:menu.item>
            @else
                @php
                    $locales = getLocalesManta();
                @endphp
                @if (isset($fields['locale']['active']) && $fields['locale']['active'] && count($locales) > 1 && $translatable)
                    @foreach ($locales as $value_locale)
                        {{-- @if ($value_locale['active']) --}}
                        @php
                            $icon = 'eye';
                            $default_locale = getLocaleManta();
                            $route = route($route_name . '.update', [$route_name => $item]);
                            if ($default_locale != $value_locale['locale']) {
                                $item_translate = $moduleClass
                                    ::where(['pid' => $item->id, 'locale' => $value_locale['locale']])
                                    ->first();
                                if ($item_translate) {
                                    $route = route($route_name . '.update', [$route_name => $item_translate]);
                                } else {
                                    $icon = 'plus-circle';
                                    $route = route($route_name . '.create', [
                                        'pid' => $item,
                                        'locale' => $value_locale['locale'],
                                    ]);
                                }
                            }
                        @endphp
                        <flux:menu.item icon="{{ $icon }}" href="{!! $route !!}">
                            {{ $value_locale['locale'] }}</flux:menu.item>
                        {{-- @endif --}}
                    @endforeach
                @else
                    <flux:menu.item icon="pencil-square"
                        href="{{ route($route_name . '.update', [$route_name => $item]) }}">
                        Aanpassen</flux:menu.item>
                @endif
                <flux:menu.separator />
                @if ($maps)
                    <flux:menu.item icon="globe-alt" href="{{ route($route_name . '.maps', [$route_name => $item]) }}">
                        Maps
                    </flux:menu.item>
                @endif
                @if (isset($fields['uploads']['active']) && $fields['uploads']['active'])
                    <flux:menu.item icon="arrow-up-on-square-stack"
                        href="{{ route($route_name . '.upload', [$route_name => $item]) }}">
                        Uploads</flux:menu.item>
                @endif

                @if (!isset($item->locked) || !$item->locked)
                    <flux:menu.item wire:click="remove" icon="trash" variant="danger">Verwijderen
                    </flux:menu.item>
                @endif

            @endif
        </flux:menu>
    </flux:dropdown>
    <flux:modal name="member-remove" class="min-w-[22rem]">
        <form class="space-y-6" wire:submit="$parent.deleteConfirm({{ $item->id }})">
            <div>
                <flux:heading size="lg">Verwijderen?</flux:heading>
                <flux:subheading>
                    <p>Weet je zeker dat je <strong>{{ $item->title }}</strong> wilt verwijderen?
                    </p>
                </flux:subheading>
            </div>
            <div class="flex">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost" class="mr-3">Annuleren</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="danger">Verwijderen</flux:button>
            </div>
        </form>
    </flux:modal>
    <flux:modal name="member-restore" class="min-w-[22rem]">
        <form class="space-y-6" wire:submit="$parent.restoreConfirm({{ $item->id }})">
            <div>
                <flux:heading size="lg">Herstellen?</flux:heading>
                <flux:subheading>
                    <p>Weet je zeker dat je <strong>{{ $item->title }}</strong> wilt herstellen?
                    </p>
                </flux:subheading>
            </div>
            <div class="flex">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost" class="mr-3">Annuleren</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="danger">Herstellen</flux:button>
            </div>
        </form>
    </flux:modal>
</flux:table.cell>
