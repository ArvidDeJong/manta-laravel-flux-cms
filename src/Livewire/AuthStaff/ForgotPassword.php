<?php

namespace Manta\FluxCMS\Livewire\AuthStaff;

use Illuminate\Support\Facades\Password;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('manta-cms::layouts.guest')]
class ForgotPassword extends Component
{
    public string $email = '';
    public bool $emailSent = false;

    protected $rules = [
        'email' => 'required|email',
    ];

    public function sendResetLink()
    {
        $this->validate();

        // Gebruik de 'staff' password broker in plaats van de standaard
        $status = Password::broker('staff')->sendResetLink(
            ['email' => $this->email]
        );

        if ($status === Password::RESET_LINK_SENT) {
            $this->emailSent = true;
        } else {
            $this->addError('email', __('Kon geen reset-link verzenden naar dit e-mailadres.'));
        }
    }

    public function render()
    {
        return view('manta-cms::livewire.auth-staff.forgot-password');
    }
}
