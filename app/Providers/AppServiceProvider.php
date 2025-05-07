<?php

namespace App\Providers;

use App\Models\Board;
use App\Models\Column;
use App\Models\Card;
use App\Policies\BoardPolicy;
use App\Policies\ColumnPolicy;
use App\Policies\CardPolicy;
use App\Observers\ActivityObserver;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     */
    protected $policies = [
        Board::class => BoardPolicy::class,
        Column::class => ColumnPolicy::class,
        Card::class => CardPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        Task::observe(TaskObserver::class);
        \App\Models\Project::observe(\App\Observers\ProjectObserver::class);
        \App\Models\Task::observe(\App\Observers\ActivityObserver::class);
    }
}
