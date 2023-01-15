<?php

namespace App\Providers;

use App\Repositories\CustomerRepository;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Repositories\Interfaces\AgentRepositoryInterface;
use App\Repositories\AgentRepository;
use App\Services\AgentService;
use App\Services\CustomerService;
use App\Services\Interfaces\AgentServiceInterface;
use App\Services\Interfaces\CustomerServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * Bind the AgentRepositoryInterface to the AgentRepository
         */
        $this->app->bind(AgentRepositoryInterface::class, AgentRepository::class);

        /**
         * Bind the AgentServiceInterface to the AgentService
         */
        $this->app->bind(AgentServiceInterface::class, AgentService::class);

        /**
         * Bind the CustomerRepositoryInterface to the CustomerRepository
         */
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);

        /**
         * Bind the CustomerServiceInterface to the CustomerService
         */
        $this->app->bind(CustomerServiceInterface::class, CustomerService::class);


    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
