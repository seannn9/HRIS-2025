<?php

namespace App\Providers;

use App\Models\LeaveRequest;
use App\Policies\LeaveRequestPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class LeaveRequestServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {    
        Gate::policy(LeaveRequest::class, LeaveRequestPolicy::class);
    }
}
