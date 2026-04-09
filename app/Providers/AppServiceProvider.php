<?php

namespace App\Providers;

use App\Models\PegawaiSkpDuaTahun;
use App\Policies\PegawaiSkpDuaTahunPolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
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
        Paginator::useBootstrapFour();

        Gate::policy(PegawaiSkpDuaTahun::class, PegawaiSkpDuaTahunPolicy::class);
    }
}
