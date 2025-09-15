<?php

namespace Manta\FluxCMS\Livewire\AuthStaff;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Manta\FluxCMS\Models\Staff;
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

        if (env('APP_ENV') == 'local') {
            // Controleer eerst of er überhaupt staff gebruikers zijn
            $staffCount = Staff::count();
            if ($staffCount === 0) {
                $this->addError('email', 'Er zijn nog geen staff gebruikers aangemaakt. Voer "php artisan manta:create-staff" uit om een staff gebruiker aan te maken.');
                logger('Login attempt failed: No staff users exist in database');
                return;
            }

            // Controleer of er actieve staff gebruikers zijn
            $activeStaffCount = Staff::where('active', true)->count();
            if ($activeStaffCount === 0) {
                $this->addError('email', 'Er zijn geen actieve staff gebruikers. Controleer de database of voer "php artisan manta:create-staff" uit.');
                logger('Login attempt failed: No active staff users exist in database');
                return;
            }
        } else {
            $staffCount = Staff::count();
            if ($staffCount === 0) {
                $this->addError('email', 'Oeps! Neem contact op met de melding: STAFF-001');
                logger('Login attempt failed: No staff users exist in database');
                return;
            }
        }

        // Gebruik de 'staff' auth guard voor staff inlog
        if (Auth::guard('staff')->attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            logger('Login successful for: ' . $this->email);
            // ERNA
            session()->regenerate();
            // Update lastLogin veld en log de login
            /** @var Staff $staff */
            $staff = Auth::guard('staff')->user();
            $staff->lastLogin = now();
            $staff->save();

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

        logger('Login failed for: ' . $this->email);

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
