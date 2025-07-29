<?php

namespace Manta\FluxCMS\Livewire\AuthStaff;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Manta\FluxCMS\Models\Staff;

#[Layout('manta-cms::layouts.guest')]
class Register extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:staff',
        'password' => 'required|string|min:8|confirmed',
    ];

    public function register()
    {
        $this->validate();

        // Staff-lid aanmaken
        $staff = Staff::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'active' => false, // Standaard niet actief tot goedkeuring admin
        ]);

        // Inloggen met staff guard
        Auth::guard('staff')->login($staff);

        // Doorverwijzen naar dashboard of andere pagina
        return redirect()->intended(route(config('manta-cms.staff_home', 'flux-cms.dashboard')));
    }

    public function render()
    {
        return view('manta-cms::livewire.auth-staff.register');
    }
}
