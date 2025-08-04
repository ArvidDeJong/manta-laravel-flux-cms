<?php

namespace Manta\FluxCMS\Livewire\Cms;

use Livewire\Component;

class CmsAlert extends Component
{
    public string $type = 'info';
    public string $message = '';
    public bool $visible = true;
    public bool $remove = true;
    public string $alertClasses;
    public string $icon;

    public function mount()
    {
        $this->setAlertProperties();
    }

    public function showAlert(string $type, string $message)
    {
        $this->type = $type;
        $this->message = $message;
        $this->visible = true;
        $this->setAlertProperties();
    }

    public function closeAlert()
    {
        $this->visible = false;
    }

    public function setAlertProperties()
    {
        $this->alertClasses = match ($this->type) {
            'success' => 'bg-green-100 border-green-500 text-green-700',
            'error' => 'bg-red-100 border-red-500 text-red-700',
            'warning' => 'bg-yellow-100 border-yellow-500 text-yellow-700',
            default => 'bg-blue-100 border-blue-500 text-blue-700',
        };

        $this->icon = match ($this->type) {
            'success' => 'fa-check-circle',
            'error' => 'fa-exclamation-circle',
            'warning' => 'fa-exclamation-triangle',
            default => 'fa-info-circle',
        };
    }

    public function render()
    {
        return view('manta-cms::livewire.cms.cms-alert');
    }
}
