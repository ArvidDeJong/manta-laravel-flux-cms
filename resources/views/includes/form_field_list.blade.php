<form wire:submit="save">
    <div class="space-y-6">
        @foreach ($fields as $key => $value)
            @if (isset($value['type']) &&
                    (!isset($value['active']) || $value['active'] == true) &&
                    (!isset($value['edit']) || $value['edit'] == true))
                @if ($value['type'] == 'text' || $value['type'] == 'email' || $value['type'] == 'password')
                    <flux:field>
                        @if (isset($value['required']) && $value['required'] == true)
                            <flux:label badge="Verplicht">{{ $value['title'] }}</flux:label>
                        @else
                            <flux:label>{{ $value['title'] }}</flux:label>
                        @endif
                        @if ($value['type'] == 'password')
                            <flux:input type="password" viewable wire:model="{{ $key }}" />
                        @else
                            <flux:input wire:model="{{ $key }}" type="{{ $value['type'] }}" />
                        @endif

                        <flux:error name="{{ $key }}" />

                        @if (isset($locale) && $locale != getLocaleManta() && $itemOrg)
                            <flux:description>Vertaling van: {{ $itemOrg->$key }}</flux:description>
                        @endif
                    </flux:field>
                @elseif ($value['type'] == 'number')
                    <flux:field>
                        @if (isset($value['required']) && $value['required'] == true)
                            <flux:label badge="Verplicht">{{ $value['title'] }}</flux:label>
                        @else
                            <flux:label>{{ $value['title'] }}</flux:label>
                        @endif
                        <flux:input wire:model="{{ $key }}" type="{{ $value['type'] }}"
                            step="{{ isset($value['step']) ? $value['step'] : 1 }}" />
                        <flux:error name="{{ $key }}" />
                    </flux:field>
                @elseif ($value['type'] == 'array')
                    <flux:heading>{{ $value['title'] }} </flux:heading>

                    <div x-data="{
                        tags: @entangle($key).live,
                        newTag: '',
                        addTag() {
                            if (this.newTag.trim() !== '') {
                                this.tags.push(this.newTag.trim());
                                this.newTag = '';
                            }
                        },
                        removeTag(tagToRemove) {
                            this.tags = this.tags.filter(tag => tag !== tagToRemove);
                        }
                    }">
                        <div class="flex flex-wrap gap-2 rounded-md border p-2">
                            <template x-for="tag in tags" :key="tag">
                                <span
                                    class="inline-flex items-center rounded bg-blue-100 px-2 py-1 text-sm text-blue-800">
                                    <span x-text="tag"></span>
                                    <button type="button" @click="removeTag(tag)" class="ml-1">&times;</button>
                                </span>
                            </template>

                            <input type="text" x-model="newTag" @keydown.enter.prevent="addTag"
                                @keydown.space.prevent="addTag" @keydown.comma.prevent="addTag"
                                class="m-0 w-20 border-0 bg-transparent p-0 outline-none focus:ring-0"
                                placeholder="Voeg toe...">
                        </div>
                    </div>
                @elseif ($value['type'] == 'date')
                    <flux:field>
                        @if ($value['required'])
                            <flux:label badge="Verplicht">{{ $value['title'] }}</flux:label>
                        @else
                            <flux:label>{{ $value['title'] }}</flux:label>
                        @endif
                        <flux:input wire:model="{{ $key }}" type="{{ $value['type'] }}" />
                        <flux:error name="{{ $key }}" />
                    </flux:field>
                @elseif ($value['type'] == 'datetime-local')
                    <flux:field>
                        @if ($value['required'])
                            <flux:label badge="Verplicht">{{ $value['title'] }}</flux:label>
                        @else
                            <flux:label>{{ $value['title'] }}</flux:label>
                        @endif
                        <flux:input wire:model="{{ $key }}" type="datetime-local" />
                        <flux:error name="{{ $key }}" />
                    </flux:field>
                @elseif ($value['type'] == 'checkbox')
                    <flux:checkbox wire:model="{{ $key }}" label="{{ $value['title'] }}" />
                @elseif ($value['type'] == 'checklist')
                    <flux:field>
                        <flux:checkbox.group label="{{ $value['title'] }} ">
                            @foreach ($value['options'] as $key2 => $value2)
                                <flux:checkbox wire:model="{{ $key }}.{{ $key2 }}"
                                    label="{{ $value2 }}" />
                            @endforeach
                        </flux:checkbox.group>
                    </flux:field>
                @elseif ($value['type'] == 'locale')
                    @php
                        $locales = [];
                        foreach (explode(',', env('SUPPORTED_LOCALES')) as $locale) {
                            $locales[$locale] = $locale;
                        }
                    @endphp
                    <flux:field>
                        <flux:label>{{ $value['title'] }}</flux:label>
                        <flux:select wire:model="{{ $key }}" size="sm"
                            placeholder="{{ $value['title'] }}...">
                            @foreach ($locales as $key1 => $value1)
                                <flux:select.option value="{{ $key1 }}">{{ $value1 }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                    </flux:field>
                @elseif ($value['type'] == 'routes')
                    @php
                        $pages = Darvis\MantaPage\Models\Page::orderBy('description', 'ASC')->get();
                        // Alle routes ophalen waarvan de naam begint met 'website.'

                        $routes = collect(Illuminate\Support\Facades\Route::getRoutes())->filter(function ($route) {
                            return (str_starts_with($route->getName(), 'nl.website.') ||
                                str_starts_with($route->getName(), 'website.')) &&
                                empty($route->parameterNames());
                        });
                    @endphp
                    <flux:field>
                        <flux:label>Link naar:</flux:label>
                        <flux:select wire:model="{{ $key }}" size="sm"
                            placeholder="{{ $value['title'] }}...">
                            <flux:select.option>Kies</flux:select.option>
                            <optgroup label="Tekst">
                                @foreach ($pages as $key1 => $value1)
                                    <flux:select.option value="page.{{ $value1->id }}">{{ $value1->description }}
                                    </flux:select.option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Pagina">
                                @foreach ($routes as $key1 => $value1)
                                    <flux:select.option value="{{ $value1->getName() }}">
                                        {{ ucfirst(Illuminate\Support\Str::replaceFirst('website.', '', Illuminate\Support\Str::replaceFirst('nl.website.', '', $value1->getName()))) }}
                                    </flux:select.option>
                                @endforeach
                            </optgroup>
                        </flux:select>
                    </flux:field>
                @elseif ($value['type'] == 'select' && isset($value['options']))
                    {{-- {!! dd($value) !!} --}}
                    <flux:field>
                        <flux:label>{{ $value['title'] }}</flux:label>
                        <flux:select variant="listbox" wire:model="{{ $key }}" size="sm"
                            placeholder="{{ $value['title'] }}...">
                            @foreach ($value['options'] as $key1 => $value1)
                                <flux:select.option value="{{ $key1 }}">{{ $value1 }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                    </flux:field>
                @elseif ($value['type'] == 'textarea')
                    <flux:field>
                        @if (isset($value['required']) && $value['required'])
                            <flux:label badge="Verplicht">{{ $value['title'] }}</flux:label>
                        @else
                            <flux:label>{{ $value['title'] }}</flux:label>
                        @endif
                        <flux:textarea wire:model="{{ $key }}" rows="auto" />
                        <flux:error name="{{ $key }}" />
                    </flux:field>
                @elseif ($value['type'] == 'editor')
                    <flux:editor wire:model="{{ $key }}" label="{{ $value['title'] }}" />
                @elseif ($value['type'] == 'tinymce')
                    <x-manta.forms.wysiwyg title="{{ $value['title'] }}" name="{{ $key }}"
                        :type="$value['type']" />
                @elseif ($value['type'] == 'tinymce-email')
                    <x-manta.forms.wysiwyg title="{{ $value['title'] }}" name="{{ $key }}" :type="$value['type']"
                        email />
                @elseif ($value['type'] == 'divider')
                    <flux:separator text="{{ $value['title'] }}" />
                @endif
            @endif
        @endforeach

        @if (count($data_fields) > 0)
            <flux:tabs class="px-4" wire:model="data_locale">
                @foreach (explode(',', env('SUPPORTED_LOCALES')) as $locale)
                    <flux:tab name="{{ $locale }}" wire:click="set_data_locale( '{{ $locale }}')">
                        {{ strtoupper($locale) }}
                    </flux:tab>
                @endforeach
            </flux:tabs>
            @foreach ($data_fields as $key => $datafield)
                @if ($datafield['type'] == 'string')
                    <flux:input wire:model.live="data_content.{{ $key }}"
                        label="{{ $datafield['title'] }}" />
                @elseif ($datafield['type'] == 'route')
                    <flux:select wire:model.live="data_content.{{ $key }}" label="{{ $datafield['title'] }}"
                        placeholder="Kies pagina">
                        <flux:select.option value="">Maak een keuze</flux:select.option>
                        @foreach (getRoutesManta() as $route)
                            <flux:select.option value="{{ $route }}">{{ $route }}</flux:select.option>
                        @endforeach
                    </flux:select>
                @endif
            @endforeach
        @endif

        @include('manta-cms::includes.form_error')
        <div class="mt-4">

            <flux:button type="submit" variant="primary">Opslaan</flux:button>

            <flux:button type="button" href="{{ route($module_routes['list']) }}">Terug naar lijst</flux:button>
        </div>
    </div>
</form>
