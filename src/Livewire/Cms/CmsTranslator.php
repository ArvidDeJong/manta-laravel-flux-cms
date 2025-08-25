<?php

namespace Manta\FluxCMS\Livewire\Cms;

use Manta\FluxCMS\Models\Translation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CmsTranslator extends Component
{
    public string $string;
    public string $content;
    public string $locale;
    public bool $showString = false;
    public bool $editing = false;
    public ?string $style = null;

    public function mount()
    {
        $this->locale = app()->getLocale();

        // Kijk eerst of er een aangepaste vertaling in de database staat
        $translation = Translation::where('key', $this->string)
            ->where('file', explode('.', $this->string)[0])
            ->where('locale', $this->locale)
            ->first();

        if ($translation) {
            $this->content = $translation->value;
        } elseif ($this->showString) {
            $this->content = $this->string;
        } else {
            // Gebruik de standaard vertaling als er geen database override is
            $this->content = __($this->string, [], $this->locale);
        }
    }

    public function render()
    {
        return view('manta-cms::livewire.cms.cms-translator');
    }

    public function edit()
    {
        $this->editing = true;
    }

    public function save()
    {
        $file = explode('.', $this->string)[0];
        $staffName = auth('staff')->user()->name;

        $translation = Translation::updateOrCreate(
            [
                'key' => $this->string,
                'file' => $file,
                'locale' => $this->locale
            ],
            [
                'value' => $this->content,
                'updated_by' => $staffName
            ]
        );

        if (is_null($translation->created_by)) {
            $translation->update(['created_by' => $staffName]);
        }

        $this->editing = false;
    }
}
