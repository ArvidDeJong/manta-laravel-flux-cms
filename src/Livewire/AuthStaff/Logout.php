<?php

namespace Manta\FluxCMS\Livewire\AuthStaff;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('manta-cms::layouts.guest')]
class Logout extends Component
{
    public function mount()
    {
        // Uitloggen van beide guards: staff en web
        if (Auth::guard('staff')->check()) {
            Auth::guard('staff')->logout();
        }
        
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }
        
        // Voor alle zekerheid ook de default guard uitloggen
        if (Auth::check()) {
            Auth::logout();
        }
        
        // Sessie invalideren en token regenereren
        session()->invalidate();
        session()->regenerateToken();
        
        return redirect(route('flux-cms.staff.login'));
    }
    
    public function render()
    {
        return view('manta-cms::livewire.auth-staff.logout');
    }
}
