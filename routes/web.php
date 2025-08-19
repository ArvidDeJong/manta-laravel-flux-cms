<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Livewire\Livewire;


Route::middleware(['web', 'auth:staff'])->prefix(config('manta-cms.routes.prefix'))
    ->name('manta-cms.')
    ->group(function () {
        Route::get('/manta-nav', \Manta\FluxCMS\Livewire\MantaNav\MantaNavList::class)->name('manta-nav.list');

        Route::get('/', \Manta\FluxCMS\Livewire\Dashboard::class)->name('dashboard');
        Route::get('/medewerkers', \Manta\FluxCMS\Livewire\Staff\StaffList::class)->name('staff.list');
        Route::get('/medewerkers/create', \Manta\FluxCMS\Livewire\Staff\StaffCreate::class)->name('staff.create');
        Route::get('/medewerkers/{staff}/rights', \Manta\FluxCMS\Livewire\Staff\StaffRights::class)->name('staff.rights');
        Route::get('/medewerkers/{staff}/update', \Manta\FluxCMS\Livewire\Staff\StaffUpdate::class)->name('staff.update');
        Route::get('/medewerkers/{staff}/bekijken', \Manta\FluxCMS\Livewire\Staff\StaffRead::class)->name('staff.read');

        Route::get('/bedrijven', \Manta\FluxCMS\Livewire\Company\CompanyList::class)->name('company.list');
        Route::get('/bedrijven/create', \Manta\FluxCMS\Livewire\Company\CompanyCreate::class)->name('company.create');
        Route::get('/bedrijven/{company}/update', \Manta\FluxCMS\Livewire\Company\CompanyUpdate::class)->name('company.update');
        Route::get('/bedrijven/{company}/bekijken', \Manta\FluxCMS\Livewire\Company\CompanyRead::class)->name('company.read');
        Route::get('/bedrijven/{company}/upload', \Manta\FluxCMS\Livewire\Company\CompanyUpload::class)->name('company.upload');

        Route::get('/gebruikers', \Manta\FluxCMS\Livewire\User\UserList::class)->name('user.list');
        Route::get('/gebruikers/toevoegen', \Manta\FluxCMS\Livewire\User\UserCreate::class)->name('user.create');
        Route::get('/gebruikers/aanpassen/{user}', \Manta\FluxCMS\Livewire\User\UserUpdate::class)->name('user.update');
        Route::get('/gebruikers/bekijken/{user}', \Manta\FluxCMS\Livewire\User\UserRead::class)->name('user.read');

        Route::get('/modules', \Manta\FluxCMS\Livewire\MantaModule\MantaModuleList::class)->name('manta-module.list');
        Route::get('/modules/create', \Manta\FluxCMS\Livewire\MantaModule\MantaModuleCreate::class)->name('manta-module.create');
        Route::get('/modules/{mantaModule}/aanpassen', \Manta\FluxCMS\Livewire\MantaModule\MantaModuleUpdate::class)->name('manta-module.update');
        Route::get('/modules/{mantaModule}/bekijken', \Manta\FluxCMS\Livewire\MantaModule\MantaModuleRead::class)->name('manta-module.read');

        Route::get("/upload", \Manta\FluxCMS\Livewire\Upload\UploadList::class)->name('upload.list');
        Route::get("/upload/toevoegen", \Manta\FluxCMS\Livewire\Upload\UploadCreate::class)->name('upload.create');
        Route::get("/upload/dropzone", \Manta\FluxCMS\Livewire\Upload\UploadDropzone::class)->name('upload.dropzone');
        Route::get("/upload/aanpassen/{upload}", \Manta\FluxCMS\Livewire\Upload\UploadUpdate::class)->name('upload.update');
        Route::get("/upload/lezen/{upload}", \Manta\FluxCMS\Livewire\Upload\UploadRead::class)->name('upload.read');
        Route::get("/upload/crop/{upload}", \Manta\FluxCMS\Livewire\Upload\UploadCrop::class)->name('upload.crop');

        // Algemene logout route voor staff
        Route::get('/logout', function () {
            if (auth('staff')->check()) {
                return redirect()->route('manta-cms.staff.logout');
            }
            return redirect('/'); // Als niemand is ingelogd, ga naar homepage
        })->name('logout');
    });

Route::middleware('web')
    ->name('flux-cms.')
    ->group(function () {
        Route::get('/staff/login', \Manta\FluxCMS\Livewire\AuthStaff\LoginForm::class)->name('staff.login');

        // Alias voor auth middleware redirect
        Route::get('/login', function () {
            return redirect()->route('flux-cms.staff.login');
        })->name('login');
        Route::get('/staff/forgot-password', \Manta\FluxCMS\Livewire\AuthStaff\ForgotPassword::class)->name('staff.forgot-password');
        Route::get('/staff/reset-password', \Manta\FluxCMS\Livewire\AuthStaff\ResetPassword::class)->name('staff.reset-password');
        Route::get('/staff/verify-email', \Manta\FluxCMS\Livewire\AuthStaff\VerifyEmail::class)->name('staff.verify-email');
        Route::get('/staff/logout', \Manta\FluxCMS\Livewire\AuthStaff\Logout::class)->name('staff.logout');


        Route::get('/account/login', \Manta\FluxCMS\Livewire\Auth\LoginForm::class)->name('account.login');
        // Route::get('/account/register', \Manta\FluxCMS\Livewire\Auth\Register::class)->name('account.register');
        Route::get('/account/forgot-password', \Manta\FluxCMS\Livewire\Auth\ForgotPassword::class)->name('account.forgot-password');
        Route::get('/account/reset-password', \Manta\FluxCMS\Livewire\Auth\ResetPassword::class)->name('account.reset-password');
        Route::get('/account/verify-email', \Manta\FluxCMS\Livewire\Auth\VerifyEmail::class)->name('account.verify-email');
        Route::get('/account/logout', \Manta\FluxCMS\Livewire\Auth\Logout::class)->name('account.logout');



        // Routes die authenticatie vereisen
        Route::middleware(['auth'])->group(function () {
            Route::get('/test-dashboard', \Manta\FluxCMS\Livewire\Web\WebDashboard::class)->name('test-dashboard');
        });

        // Sitemap for public pages
        Route::get('/sitemap.xml', [\Manta\FluxCMS\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

        // Clear settings
        Route::get('/clearDgP', function () {
            Artisan::call('cache:clear'); // Cache legen
            Artisan::call('route:clear'); // Routes cache legen
            Artisan::call('config:clear'); // Config cache legen
            Artisan::call('view:clear'); // Views cache legen
            Artisan::call('storage:link', []); // Symlink voor storage aanmaken
            Artisan::call('event:clear'); // Events cache legen
            Artisan::call('optimize:clear'); // Optimalisatie caches legen
            return response()->json([
                'status' => 'success',
                'message' => 'Alle caches en links zijn succesvol geleegd en opnieuw aangemaakt.',
            ]);
        });
    });
