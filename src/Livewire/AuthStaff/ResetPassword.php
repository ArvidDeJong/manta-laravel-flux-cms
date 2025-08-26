<?php

namespace Manta\FluxCMS\Livewire\AuthStaff;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
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

    public function mount($token = null)
    {
        $this->token = $token ?? request()->route('token') ?? request()->get('token') ?? '';

        if (request()->has('email')) {
            $this->email = request()->get('email');
        }
    }

    public function resetPassword()
    {
        $this->validate();

        // Gebruik de 'staff' password broker voor wachtwoord reset
        $status = Password::broker('staff')->reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));

                Auth::guard('staff')->login($user);
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->intended(route('manta-cms.dashboard'))
                ->with('status', __('Wachtwoord is succesvol gereset.'));
        }

        $this->addError('email', __($status));
    }

    public function render()
    {
        return view('manta-cms::livewire.auth-staff.reset-password');
    }
}
