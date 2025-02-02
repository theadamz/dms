<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    protected array $repositories = [
        \App\Repositories\Contracts\AccessRepository::class => \App\Repositories\AccessRepository::class,
        \App\Repositories\Contracts\DocumentRepository::class => \App\Repositories\DocumentRepository::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        foreach ($this->repositories as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
