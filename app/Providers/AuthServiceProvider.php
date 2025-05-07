<?php

namespace App\Providers;

use App\Models\CalendarEvent;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // Map your models to their respective policies
        CalendarEvent::class => 'App\Policies\CalendarEventPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Define the delete permission for CalendarEvent
        Gate::define('delete-calendar-event', function ($user, CalendarEvent $event) {
            return $user->id === $event->user_id; // Example logic
        });
    }
}
