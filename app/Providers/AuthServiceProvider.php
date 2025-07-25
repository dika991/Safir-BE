<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        Passport::tokensCan([
            'user' => 'User Type',
            'admin' => 'Admin User Type',
            'superAdmin' => "Task for Super Admin",
            'operationalAdmin' => 'Task for only operationalAdmin',
            'inventarisAdmin' => 'Task for only inventaris',
            'accountingAdmin' => 'Task for accounting'
        ]);
    }
}
