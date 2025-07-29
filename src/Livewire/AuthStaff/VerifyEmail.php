<?php

namespace Manta\FluxCMS\Livewire\AuthStaff;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('manta-cms::layouts.guest')]
class VerifyEmail extends Component
{
    public function render()
    {
        return view('manta-cms::livewire.auth-staff.verify-email', [
            'staff' => Auth::guard('staff')->user(),
        ]);
    }

    public function resend()
    {
        $staff = Auth::guard('staff')->user();

        if ($staff && !$staff->hasVerifiedEmail()) {
            $staff->sendEmailVerificationNotification();
            
            session()->flash('status', __('Verificatie link is opnieuw verzonden.'));
        }
    }
}
