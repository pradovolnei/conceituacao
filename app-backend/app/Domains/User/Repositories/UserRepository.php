<?php
namespace App\Domains\User\Repositories;

use App\Domains\User\DataTransfers\UserCreateDTO;
use App\Domains\User\DataTransfers\UserUpdateDTO;
use App\Domains\User\Models\User;
use App\Domains\User\Repositories\Interfaces\UserRepositoryInterface;
use App\Domains\User\Requests\UserCreateRequest;
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

    /**
     * @param UserCreateDTO $data
     * @return User
     */
    public function create(UserCreateDTO $data): User
    {
        $data->password = bcrypt($data->password);
        return $this->model->create($data->toArray());
    }

    public function update(UserUpdateDTO $data, int $id)
    {
       $model = $this->model->find($id);
       $model->fill($data->toArray());
       $model->save();

       return $this->findById($id);
    }

    public function delete(int $id)
    {
        // TODO: Implement delete() method.
    }

    public function findById(int $id)
    {
        return $this->model->find($id);
    }

}
