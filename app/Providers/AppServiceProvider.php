<?php

namespace App\Providers;

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

    public function boot(): void
    {
        \Illuminate\Database\Eloquent\Relations\Relation::enforceMorphMap([
            'admin' => 'App\Models\AdminUser',
            'member' => 'App\Models\Member',
            'information' => 'App\Models\Information',
            'system' => 'App\Models\AuditLog', // Placeholder to avoid "Class system not found" error
        ]);
    }
}
