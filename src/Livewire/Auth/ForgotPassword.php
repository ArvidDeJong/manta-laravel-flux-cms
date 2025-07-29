<?php

namespace Manta\FluxCMS\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('manta-cms::layouts.guest')]
class ForgotPassword extends Component
{
    public string $email = '';

    protected $rules = [
        'email' => 'required|email',
    ];

    public function sendResetLink()
    {
        $this->validate();

        $status = Password::sendResetLink(
            ['email' => $this->email]
        );

        if ($status === Password::RESET_LINK_SENT) {
            $this->dispatch('notify', [
                'message' => __('We hebben een e-mail gestuurd met instructies om je wachtwoord opnieuw in te stellen.'),
                'type' => 'success',
            ]);
            $this->reset('email');
        } else {
            $this->addError('email', __('Er is een probleem opgetreden bij het versturen van de e-mail. Probeer het later nog eens.'));
        }
    }

    public function render()
    {
        return view('manta-cms::livewire.auth.forgot-password');
    }
}
