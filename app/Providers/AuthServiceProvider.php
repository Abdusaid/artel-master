<?php

namespace App\Providers;

use App\Import;
use App\Export;
use Illuminate\Support\Facades\Gate;
use App\Policies\Sklad\ExportPolicy;
use App\Policies\Sklad\ImportPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Import::class => ImportPolicy::class,
        Export::class => ExportPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        
        //
    }
}
