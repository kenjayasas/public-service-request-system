<?php

namespace App\Providers;

use App\Models\Feedback;
use App\Models\ServiceRequest;
use App\Policies\FeedbackPolicy;
use App\Policies\ServiceRequestPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        ServiceRequest::class => ServiceRequestPolicy::class,
        Feedback::class => FeedbackPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}