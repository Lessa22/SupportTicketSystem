<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\TicketRepositoryInterface;
use App\Repositories\TicketRepository;
use App\Models\Ticket;
use App\Observers\TicketObserver;
use App\Policies\TicketPolicy;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            TicketRepositoryInterface::class,
            TicketRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Ticket::observe(TicketObserver::class);
        Gate::policy(Ticket::class, TicketPolicy::class);
    }
}
