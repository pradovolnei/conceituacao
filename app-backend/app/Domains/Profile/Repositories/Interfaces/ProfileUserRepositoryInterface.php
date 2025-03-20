<?php
namespace App\Domains\Profile\Repositories\Interfaces;

use App\Domains\Profile\DataTransfers\ProfileUserCreateDTO;

interface ProfileUserRepositoryInterface
{
    public function getProfilesByUser(int $userId);

    public function attachProfile(ProfileUserCreateDTO $data);

    public function detachProfile(ProfileUserCreateDTO $data);
}
