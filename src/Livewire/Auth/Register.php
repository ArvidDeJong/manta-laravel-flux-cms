<?php

namespace Manta\FluxCMS\Livewire\Auth;

use Manta\FluxCMS\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('manta-cms::layouts.guest')]
class Register extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ];

    public function register()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        // Als e-mailverificatie vereist is, stuur dan een verificatiemail
        if (config('manta.user_verify')) {
            $user->sendEmailVerificationNotification();
        }

        // Automatisch inloggen na registratie
        Auth::login($user);

        // Redirect naar dashboard of verificatiepagina indien verificatie vereist is
        if (config('manta.user_verify')) {
            return redirect()->route('verification.notice');
        } else {
            return redirect()->route(config('manta.user_home'));
        }
    }

    public function render()
    {
        return view('manta-cms::livewire.auth.register');
    }
}
