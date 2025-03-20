<?php

namespace App\Domains\Profile\Providers;

use App\Domains\Profile\Repositories\ProfileRepository;
use App\Domains\Profile\Repositories\Interfaces\ProfileRepositoryInterface;
use App\Domains\Profile\Repositories\ProfileUserRepository;
use App\Domains\Profile\Repositories\Interfaces\ProfileUserRepositoryInterface;
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
            ProfileRepositoryInterface::class,
            ProfileRepository::class
        );

        $this->app->bind(
            ProfileUserRepositoryInterface::class,
            ProfileUserRepository::class
        );
    }
}
