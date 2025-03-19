<?php
namespace App\Domains\User\Repositories\Interfaces;

use App\Domains\User\DataTransfers\UserCreateDTO;
use App\Domains\User\DataTransfers\UserUpdateDTO;

interface UserRepositoryInterface
{
    public function paginate();

    public function create(UserCreateDTO $data);

    public function update(UserUpdateDTO $data, int $id);

    public function delete(int $id);
}
