<?php

namespace Manta\FluxCMS\Livewire\AuthStaff;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('manta-cms::layouts.guest')]
class LoginForm extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function login()
    {
        $this->validate();

        // Gebruik de 'staff' auth guard voor staff inlog
        if (Auth::guard('staff')->attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();

            return redirect()->intended(route('manta-cms.dashboard'));
        }

        $this->addError('email', __('De opgegeven inloggegevens zijn onjuist.'));
    }

    public function render()
    {
        return view('manta-cms::livewire.auth-staff.login-form');
    }
}
