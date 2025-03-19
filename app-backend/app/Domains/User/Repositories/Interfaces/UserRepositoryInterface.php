<?php
namespace App\Domains\User\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function paginate();

    public function create(array $attributes);

    public function update(array $attributes, int $id);

    public function delete(int $id);
}
