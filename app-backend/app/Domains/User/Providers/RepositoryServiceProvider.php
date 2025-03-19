<?php

namespace App\Domains\User\Providers;

use App\Domains\User\Repositories\UserRepository;
use App\Domains\User\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bind the interface to an implementation repository class
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );
    }
}
