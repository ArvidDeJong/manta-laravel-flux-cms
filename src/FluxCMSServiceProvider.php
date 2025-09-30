<?php

namespace Manta\FluxCMS;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Component;
use Livewire\Livewire;
use Manta\FluxCMS\Console\Commands\CopyLibrariesCommand;
use Manta\FluxCMS\Console\Commands\CreateStaffCommand;
use Manta\FluxCMS\Console\Commands\ImportModuleSettingsCommand;
use Manta\FluxCMS\Console\Commands\InstallCommand;
use Manta\FluxCMS\Console\Commands\RefreshCommand;
use Manta\FluxCMS\Console\Commands\SeedCompanyCommand;
use Manta\FluxCMS\Console\Commands\SeedNavigationCommand;
use Manta\FluxCMS\Console\Commands\SyncRoutesCommand;
use Manta\FluxCMS\Livewire\Dashboard;
use Manta\FluxCMS\View\Components\Manta\Cms\HeaderFlux;
use Manta\FluxCMS\Livewire\MantaNav\MantaNavList;
use Manta\FluxCMS\Middleware\StaffAuthenticate;
// Models
use Manta\FluxCMS\Models\Audit;
use Manta\FluxCMS\Models\Company;
use Manta\FluxCMS\Models\Contactperson;
use Manta\FluxCMS\Models\Firewall;
use Manta\FluxCMS\Models\Iplist;
use Manta\FluxCMS\Models\Mailtrap;
use Manta\FluxCMS\Models\Option;
use Manta\FluxCMS\Models\Routeseo;
use Manta\FluxCMS\Models\Staff;
use Manta\FluxCMS\Models\StaffLog;
use Manta\FluxCMS\Models\Translation;
use Manta\FluxCMS\Models\Upload;
use Manta\FluxCMS\Models\User;
use Manta\FluxCMS\Models\UserLog;
use Manta\FluxCMS\Models\Userregister;
// Services
use Manta\FluxCMS\Services\GoogleTranslateService;
use Manta\FluxCMS\Services\Manta as MantaService;
use Manta\FluxCMS\Services\Openai;
use Manta\FluxCMS\Services\PdfToImage;
use Manta\FluxCMS\Services\Sitemap;
// Traits
use Manta\FluxCMS\Traits\HasTranslationsTrait;
use Manta\FluxCMS\Traits\HasUploadsTrait;
use Manta\FluxCMS\Traits\MantaMapsTrait;
use Manta\FluxCMS\Traits\MantaPagerowTrait;
use Manta\FluxCMS\Traits\MantaTrait;
use Manta\FluxCMS\Traits\SortableTrait;
use Manta\FluxCMS\Traits\WebsiteTrait;
use Manta\FluxCMS\Traits\WithSortingTrait;

class FluxCMSServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        // Registreer de staff auth guard
        $this->registerAuthGuard();

        // Registreer middleware
        $this->registerMiddleware();

        // Authentication redirects worden geconfigureerd via het install command

        // Laad vertaalbestanden
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'manta-cms');

        // Registreer alle Livewire componenten
        $this->registerLivewireComponents();

        if (file_exists(__DIR__ . '/helpers.php')) {
            require_once __DIR__ . '/helpers.php';
        }

        // Publiceer config
        $this->publishes([
            __DIR__ . '/../config/manta-cms.php' => config_path('manta-cms.php'),
        ], 'config');

        // Publiceer views
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/manta-cms'),
        ], 'views');

        // Publiceer public assets
        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/manta-cms'),
        ], 'public');

        // Publiceer migrations
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'migrations');

        // Laad views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'manta-cms');

        // Registreer anonymous Blade componenten
        Blade::anonymousComponentPath(__DIR__ . '/../resources/views/components', 'manta');

        // Registreer PHP Blade componenten (met unieke naam om conflict te voorkomen)
        Blade::component('manta.cms.header-flux-php', HeaderFlux::class);
        
        // Registreer Webflow componenten
        Blade::component('manta.webflow-image', \Manta\FluxCMS\View\Components\WebflowImage::class);

        // Laad migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Laad routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Registreer commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                CopyLibrariesCommand::class,
                CreateStaffCommand::class,
                ImportModuleSettingsCommand::class,
                InstallCommand::class,
                RefreshCommand::class,
                SeedCompanyCommand::class,
                SeedNavigationCommand::class,
                SyncRoutesCommand::class,
            ]);
        }
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/manta-cms.php',
            'manta-cms'
        );

        // Registreer auth config
        $this->mergeConfigFrom(
            __DIR__ . '/../config/manta-auth.php',
            'manta-auth'
        );

        // Merge auth config met de standaard Laravel auth config
        $this->mergeAuthConfig();

        // Register module configs service
        $this->registerModuleConfigs();

        // Registreer models
        $this->app->bind('manta-cms.translation', function () {
            return new Translation();
        });

        // Registreer models
        $this->app->bind('manta-cms.company', function () {
            return new Company();
        });

        // Registreer models
        $this->app->bind('manta-cms.contactperson', function () {
            return new Contactperson();
        });

        // Registreer models
        $this->app->bind('manta-cms.user', function () {
            return new User();
        });

        // Registreer services
        $this->app->bind('manta-cms.google-translate-service', function () {
            return new GoogleTranslateService();
        });

        $this->app->bind('manta-cms.manta-service', function () {
            return new MantaService();
        });

        $this->app->bind('manta-cms.openai', function () {
            return new Openai();
        });

        $this->app->bind('manta-cms.pdf-to-image', function () {
            return new PdfToImage();
        });

        $this->app->bind('manta-cms.sitemap', function () {
            return new Sitemap();
        });

        // Registreer alle andere models
        $this->app->bind('manta-cms.audit', function () {
            return new Audit();
        });

        $this->app->bind('manta-cms.firewall', function () {
            return new Firewall();
        });

        $this->app->bind('manta-cms.iplist', function () {
            return new Iplist();
        });

        $this->app->bind('manta-cms.mailtrap', function () {
            return new Mailtrap();
        });

        $this->app->bind('manta-cms.option', function () {
            return new Option();
        });

        $this->app->bind('manta-cms.routeseo', function () {
            return new Routeseo();
        });

        $this->app->bind('manta-cms.staff', function () {
            return new Staff();
        });

        $this->app->bind('manta-cms.staff-log', function () {
            return new StaffLog();
        });

        $this->app->bind('manta-cms.upload', function () {
            return new Upload();
        });

        $this->app->bind('manta-cms.user-log', function () {
            return new UserLog();
        });

        $this->app->bind('manta-cms.userregister', function () {
            return new Userregister();
        });
    }

    /**
     * Registreer de module configuratie service
     */
    /**
     * Registreer de auth guard voor staff
     */
    protected function registerAuthGuard(): void
    {
        // Extend de auth config met de staff guard
        Auth::extend('staff', function ($app, $name, array $config) {
            // De config uit manta-auth.php wordt gebruikt
            return Auth::createSessionDriver($name, $config);
        });

        // Registreer de staff auth provider
        Auth::provider('staff', function ($app, array $config) {
            return new \Illuminate\Auth\EloquentUserProvider($app['hash'], $config['model']);
        });
    }

    /**
     * Registreer de module configuratie service
     */
    /**
     * Merge de auth config met de standaard Laravel auth config
     */
    protected function mergeAuthConfig(): void
    {
        // Haal onze manta-auth configuratie op via de app helper
        $mantaAuthConfig = \app('config')->get('manta-auth', []);

        if (isset($mantaAuthConfig['guards']) && is_array($mantaAuthConfig['guards'])) {
            // Voeg onze guards toe aan de auth.guards config
            foreach ($mantaAuthConfig['guards'] as $guard => $config) {
                \app('config')->set("auth.guards.{$guard}", $config);
            }
        }

        if (isset($mantaAuthConfig['providers']) && is_array($mantaAuthConfig['providers'])) {
            // Voeg onze providers toe aan de auth.providers config
            foreach ($mantaAuthConfig['providers'] as $provider => $config) {
                \app('config')->set("auth.providers.{$provider}", $config);
            }
        }

        if (isset($mantaAuthConfig['passwords']) && is_array($mantaAuthConfig['passwords'])) {
            // Voeg onze password settings toe aan de auth.passwords config
            foreach ($mantaAuthConfig['passwords'] as $broker => $config) {
                \app('config')->set("auth.passwords.{$broker}", $config);
            }
        }
    }

    /**
     * Registreer de middleware
     */
    protected function registerMiddleware(): void
    {
        // Registreer de StaffAuthenticate middleware als alias voor auth:staff
        // Dit zorgt dat bij routes met middleware 'auth:staff' de gebruiker naar staff.login wordt gestuurd wanneer niet ingelogd
        Route::aliasMiddleware('auth:staff', StaffAuthenticate::class);
    }

    /**
     * Registreer de module configuratie service
     */
    protected function registerModuleConfigs(): void
    {
        $this->app->singleton('manta.module.config', function ($app) {
            return new class($app) {
                protected $app;

                public function __construct($app)
                {
                    $this->app = $app;
                }

                public function get($name)
                {
                    // Check if app config exists
                    $configPath = $this->app['path.config'] . '/manta-module-' . $name . '.php';

                    if (file_exists($configPath)) {
                        return include($configPath);
                    }

                    // Fallback naar package configuratie
                    $packageConfigPath = dirname(__DIR__) . '/config/manta-module-' . $name . '.php';

                    if (file_exists($packageConfigPath)) {
                        return include($packageConfigPath);
                    }

                    return [];
                }
            };
        });
    }

    /**
     * Registreer alle Livewire componenten
     */
    protected function registerLivewireComponents(): void
    {
        // Dashboard component
        Livewire::component('manta-cms::dashboard', Dashboard::class);

        // User componenten
        Livewire::component('manta-cms::user.list', \Manta\FluxCMS\Livewire\User\UserList::class);
        Livewire::component('manta-cms::user.create', \Manta\FluxCMS\Livewire\User\UserCreate::class);
        Livewire::component('manta-cms::user.update', \Manta\FluxCMS\Livewire\User\UserUpdate::class);
        Livewire::component('manta-cms::user.read', \Manta\FluxCMS\Livewire\User\UserRead::class);

        // Staff componenten
        Livewire::component('manta-cms::staff.list', \Manta\FluxCMS\Livewire\Staff\StaffList::class);
        Livewire::component('manta-cms::staff.create', \Manta\FluxCMS\Livewire\Staff\StaffCreate::class);
        Livewire::component('manta-cms::staff.update', \Manta\FluxCMS\Livewire\Staff\StaffUpdate::class);
        Livewire::component('manta-cms::staff.read', \Manta\FluxCMS\Livewire\Staff\StaffRead::class);
        Livewire::component('manta-cms::staff.rights', \Manta\FluxCMS\Livewire\Staff\StaffRights::class);

        // Company componenten
        Livewire::component('manta-cms::company.list', \Manta\FluxCMS\Livewire\Company\CompanyList::class);
        Livewire::component('manta-cms::company.create', \Manta\FluxCMS\Livewire\Company\CompanyCreate::class);
        Livewire::component('manta-cms::company.update', \Manta\FluxCMS\Livewire\Company\CompanyUpdate::class);
        Livewire::component('manta-cms::company.read', \Manta\FluxCMS\Livewire\Company\CompanyRead::class);

        // Navigation component
        Livewire::component('manta-cms::manta-nav.list', MantaNavList::class);

        // Upload componenten
        Livewire::component('manta-cms::upload.list', \Manta\FluxCMS\Livewire\Upload\UploadList::class);
        Livewire::component('manta-cms::upload.create', \Manta\FluxCMS\Livewire\Upload\UploadCreate::class);
        Livewire::component('manta-cms::upload.update', \Manta\FluxCMS\Livewire\Upload\UploadUpdate::class);
        Livewire::component('manta-cms::upload.read', \Manta\FluxCMS\Livewire\Upload\UploadRead::class);
        Livewire::component('manta-cms::upload.overview', \Manta\FluxCMS\Livewire\Upload\UploadOverview::class);
        Livewire::component('manta-cms::upload.dropzone', \Manta\FluxCMS\Livewire\Upload\UploadDropzone::class);
        Livewire::component('manta-cms::upload.crop', \Manta\FluxCMS\Livewire\Upload\UploadCrop::class);
        Livewire::component('manta-cms::upload.form', \Manta\FluxCMS\Livewire\Upload\UploadForm::class);
        Livewire::component('manta-cms::livewire.upload.upload-form', \Manta\FluxCMS\Livewire\Upload\UploadForm::class);
        Livewire::component('manta-cms::livewire.upload.upload-overview', \Manta\FluxCMS\Livewire\Upload\UploadOverview::class);

        // Auth componenten voor reguliere gebruikers
        Livewire::component('manta-cms::auth.login-form', \Manta\FluxCMS\Livewire\Auth\LoginForm::class);
        Livewire::component('manta-cms::auth.register', \Manta\FluxCMS\Livewire\Auth\Register::class);
        Livewire::component('manta-cms::auth.forgot-password', \Manta\FluxCMS\Livewire\Auth\ForgotPassword::class);
        Livewire::component('manta-cms::auth.reset-password', \Manta\FluxCMS\Livewire\Auth\ResetPassword::class);
        Livewire::component('manta-cms::auth.verify-email', \Manta\FluxCMS\Livewire\Auth\VerifyEmail::class);
        Livewire::component('manta-cms::auth.logout', \Manta\FluxCMS\Livewire\Auth\Logout::class);

        // AuthStaff componenten voor staff gebruikers
        Livewire::component('manta-cms::staff.login-form', \Manta\FluxCMS\Livewire\AuthStaff\LoginForm::class);
        Livewire::component('manta-cms::staff.register', \Manta\FluxCMS\Livewire\AuthStaff\Register::class);
        Livewire::component('manta-cms::staff.forgot-password', \Manta\FluxCMS\Livewire\AuthStaff\ForgotPassword::class);
        Livewire::component('manta-cms::staff.reset-password', \Manta\FluxCMS\Livewire\AuthStaff\ResetPassword::class);
        Livewire::component('manta-cms::staff.verify-email', \Manta\FluxCMS\Livewire\AuthStaff\VerifyEmail::class);
        Livewire::component('manta-cms::staff.logout', \Manta\FluxCMS\Livewire\AuthStaff\Logout::class);

        // CMS componenten
        Livewire::component('manta-cms::cms.alert', \Manta\FluxCMS\Livewire\Cms\CmsAlert::class);
        Livewire::component('manta-cms::cms.dashboard', \Manta\FluxCMS\Livewire\Cms\CmsDashboard::class);
        Livewire::component('manta-cms::cms.footer', \Manta\FluxCMS\Livewire\Cms\CmsFooter::class);
        Livewire::component('manta-cms::cms.module-translations', \Manta\FluxCMS\Livewire\Cms\CmsModuleTranslations::class);
        Livewire::component('manta-cms::cms.numbers', \Manta\FluxCMS\Livewire\Cms\CmsNumbers::class);
        Livewire::component('manta-cms::cms.sandbox', \Manta\FluxCMS\Livewire\Cms\CmsSandbox::class);
        Livewire::component('manta-cms::cms.cms-translator', \Manta\FluxCMS\Livewire\Cms\CmsTranslator::class);

        // Web componenten
        Livewire::component('manta-cms::web.dashboard', \Manta\FluxCMS\Livewire\Web\WebDashboard::class);

        // Website componenten
        Livewire::component('manta-cms::website.inline-editor', \Manta\FluxCMS\Livewire\Website\WebsiteInlineEditor::class);
        Livewire::component('manta-cms::website.website-translator', \Manta\FluxCMS\Livewire\Website\WebsiteTranslator::class);

        // MantaModule componenten
        Livewire::component('manta-cms::manta-module.list', \Manta\FluxCMS\Livewire\MantaModule\MantaModuleList::class);
        Livewire::component('manta-cms::manta-module.create', \Manta\FluxCMS\Livewire\MantaModule\MantaModuleCreate::class);
        Livewire::component('manta-cms::manta-module.update', \Manta\FluxCMS\Livewire\MantaModule\MantaModuleUpdate::class);
        Livewire::component('manta-cms::manta-module.read', \Manta\FluxCMS\Livewire\MantaModule\MantaModuleRead::class);

        // Option componenten
        Livewire::component('manta-cms::option.update', \Manta\FluxCMS\Livewire\Option\OptionUpdate::class);

        // Routeseo componenten
        Livewire::component('manta-cms::routeseo.list', \Manta\FluxCMS\Livewire\Routeseo\RouteseoList::class);
        Livewire::component('manta-cms::routeseo.update', \Manta\FluxCMS\Livewire\Routeseo\RouteseoUpdate::class);
    }

}
