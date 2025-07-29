<?php

namespace Manta\FluxCMS\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('manta-cms::layouts.guest')]
class VerifyEmail extends Component
{
    public function render()
    {
        return view('manta-cms::livewire.auth.verify-email');
    }

    public function resendVerification()
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirect(route(config('manta.user_home')));
        }

        Auth::user()->sendEmailVerificationNotification();

        session()->flash('message', 'Verificatie link is opnieuw verzonden!');
    }
}
