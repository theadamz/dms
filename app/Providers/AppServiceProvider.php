<?php

namespace App\Providers;

use App\Helpers\GeneralHelper;
use App\Services\AccessService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * All of the container singletons that should be registered.
     *
     * @var array
     */
    public $singletons = [
        'general' => GeneralHelper::class,
        'access' => AccessService::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // custom vite
        Vite::useHotFile(public_path('vite.hot'))
            ->useBuildDirectory('assets/pages')
            ->useManifestFilename('assets.json')
            ->withEntryPoints(['resources/js/app.js']);

        // menggunakan theme bootstrap 4
        Paginator::useBootstrapFour();
    }
}
