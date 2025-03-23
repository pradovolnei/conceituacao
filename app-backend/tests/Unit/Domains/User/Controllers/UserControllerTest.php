<?php
namespace Tests\Unit\Domains\User\Controllers;

use PHPUnit\Framework\TestCase;
use Mockery;


class UserControllerTest extends TestCase
{
    private $userRepository;


    public function setUp(): void
    {
        parent::setUp();

        // create a mock of the post repository interface and inject it into the
        // IoC container
        $this->userRepository = Mockery::mock('App\Domains\User\Repositories\Interfaces\UserRepositoryInterface');
        $this->app->instance('App\Domains\User\Repositories\Interfaces\UserRepositoryInterface', $this->userRepository);

    }

    public function tearDown(): void
    {
        Mockery::close();
        $this->tearDown();
    }

    public function test_index_return_empty(): void
    {
        $this->assertTrue(true);
    }
}
