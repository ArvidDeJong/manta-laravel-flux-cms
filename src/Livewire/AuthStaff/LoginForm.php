<?php

namespace Manta\FluxCMS\Livewire\AuthStaff;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Manta\FluxCMS\Models\StaffLog;

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

            // Update lastLogin veld en log de login
            $staff = Auth::guard('staff')->user();
            $staff->update(['lastLogin' => now()]);

            // Maak een StaffLog record aan
            StaffLog::create([
                'staff_id' => $staff->id,
                'email' => $this->email,
                'ip' => request()->ip(),
                'data' => [
                    'action' => 'login',
                    'success' => true,
                    'user_agent' => request()->userAgent(),
                    'timestamp' => now()->toISOString(),
                ]
            ]);

            return redirect()->intended(route('manta-cms.dashboard'));
        }

        // Log mislukte login poging
        StaffLog::create([
            'staff_id' => null,
            'email' => $this->email,
            'ip' => request()->ip(),
            'data' => [
                'action' => 'login_failed',
                'success' => false,
                'user_agent' => request()->userAgent(),
                'timestamp' => now()->toISOString(),
            ]
        ]);

        $this->addError('email', __('De opgegeven inloggegevens zijn onjuist.'));
    }

    public function render()
    {
        return view('manta-cms::livewire.auth-staff.login-form');
    }
}
