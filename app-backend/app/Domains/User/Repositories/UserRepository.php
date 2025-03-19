<?php
namespace App\Domains\User\Repositories;

use App\Domains\User\Models\User;
use App\Domains\User\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Auth\Authenticatable;

class UserRepository implements UserRepositoryInterface
{
    private User $model;

    private ?Authenticatable $authUser;

    /**
     * @param User $event
     */
    public function __construct(User $event)
    {
        $this->model = $event;
        $this->authUser = auth('sanctum')->user();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function paginate()
    {
        return $this->model->orderBy('id', 'desc')->paginate();
    }

    public function create(array $attributes)
    {
        // TODO: Implement create() method.
    }

    public function update(array $attributes, int $id)
    {
        // TODO: Implement update() method.
    }

    public function delete(int $id)
    {
        // TODO: Implement delete() method.
    }
}
