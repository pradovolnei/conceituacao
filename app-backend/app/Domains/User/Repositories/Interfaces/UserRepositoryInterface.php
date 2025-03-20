<?php
namespace App\Domains\User\Repositories\Interfaces;

use App\Domains\User\DataTransfers\UserCreateDTO;
use App\Domains\User\DataTransfers\UserUpdateDTO;
use App\Domains\User\Models\User;

interface UserRepositoryInterface
{
    public function paginate();

    public function findById(int $id): ?User;

    public function create(UserCreateDTO $data): ?User;

    public function update(UserUpdateDTO $data, int $id);

    public function delete(int $id);
}
