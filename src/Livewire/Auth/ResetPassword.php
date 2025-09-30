<?php

namespace Manta\FluxCMS\Livewire\Auth;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('manta-cms::layouts.guest')]
class ResetPassword extends Component
{
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    protected $rules = [
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ];

    public function mount($token)
    {
        $this->token = $token;
        $this->email = request()->query('email', '');
    }

    public function resetPassword()
    {
        $this->validate();

        $status = Password::reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            session()->flash('status', __('Wachtwoord is succesvol gereset. Je kunt nu inloggen met je nieuwe wachtwoord.'));

            return redirect()->route('flux-cms.account.login');
        }

        $this->addError('email', __('Er is een probleem opgetreden bij het resetten van je wachtwoord.'));
    }

    public function render()
    {
        return view('manta-cms::livewire.auth.reset-password');
    }
}
