<?php
namespace App\Domains\User\Repositories;

use App\Domains\User\DataTransfers\UserCreateDTO;
use App\Domains\User\DataTransfers\UserUpdateDTO;
use App\Domains\User\Models\User;
use App\Domains\User\Repositories\Interfaces\UserRepositoryInterface;
use App\Domains\User\Requests\UserCreateRequest;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Exceptions\HttpResponseException;
use \Illuminate\Pagination\LengthAwarePaginator;

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
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate(): LengthAwarePaginator
    {
        return $this->model->with(['profiles'])->orderBy('id', 'desc')->paginate();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function findById(int $id)
    {
        return $this->model->find($id);
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

    /**
     * @param UserUpdateDTO $data
     * @param int $id
     * @return mixed
     */
    public function update(UserUpdateDTO $data, int $id)
    {
       $model = $this->model->find($id);
       $model->fill($data->toArray());
       $model->save();

       return $this->findById($id);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function delete(int $id)
    {
        $model = $this->findById($id);
        if (!$model) {
            throw new HttpResponseException(
                response()->json(['message' => 'usuário não encontrado'], 404)
            );
        }
        return $model->delete();
    }

}
