<?php

namespace Manta\FluxCMS\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('manta-cms::layouts.guest')]
class Logout extends Component
{
    public function mount()
    {
        // Log uit van alle geconfigureerde guards
        $guards = array_keys(config('auth.guards'));

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::guard($guard)->logout();
            }
        }

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route(config('manta.user_home', 'website.homepage'));
    }

    public function render()
    {
        return <<<'HTML'
        <form wire:submit.prevent="logout">
            <button type="submit" class="hidden" id="logout-button"></button>
        </form>
        HTML;
    }
}
