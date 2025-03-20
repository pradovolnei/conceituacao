<?php
namespace App\Domains\Profile\Repositories\Interfaces;

use App\Domains\Profile\DataTransfers\ProfileDTO;

interface ProfileRepositoryInterface
{
    public function paginate();

    public function create(ProfileDTO $data);

    public function update(ProfileDTO $data, int $id);

    public function delete(int $id);
}
