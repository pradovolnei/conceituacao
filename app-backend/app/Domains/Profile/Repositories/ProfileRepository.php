<?php
namespace App\Domains\Profile\Repositories;

use App\Domains\Profile\DataTransfers\ProfileDTO;
use App\Domains\Profile\Models\Profile;
use App\Domains\Profile\Repositories\Interfaces\ProfileRepositoryInterface;
use Illuminate\Http\Exceptions\HttpResponseException;
use \Illuminate\Pagination\LengthAwarePaginator;

class ProfileRepository implements ProfileRepositoryInterface
{
    private Profile $model;

    /**
     * @param Profile $profile
     */
    public function __construct(Profile $profile)
    {
        $this->model = $profile;
    }

    /**
     * @return LengthAwarePaginator
     */
    public function paginate(): LengthAwarePaginator
    {
        return $this->model->orderBy('id', 'desc')->paginate();
    }

    /**
     * @param int $id
     * @return Profile|null
     */
    public function findById(int $id): ?Profile
    {
        return $this->model->find($id);
    }

    /**
     * @param ProfileDTO $data
     * @return Profile
     */
    public function create(ProfileDTO $data): Profile
    {
        return $this->model->create($data->toArray());
    }

    /**
     * @param ProfileDTO $data
     * @param int $id
     * @return Profile
     */
    public function update(ProfileDTO $data, int $id): Profile
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
                response()->json(['message' => 'perfil não encontrado'], 404)
            );
        }
        return $model->delete();
    }

}
