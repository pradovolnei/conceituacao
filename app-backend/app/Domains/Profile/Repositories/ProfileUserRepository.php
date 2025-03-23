<?php
namespace App\Domains\Profile\Repositories;

use App\Domains\Profile\DataTransfers\ProfileUserCreateDTO;
use App\Domains\Profile\Models\Profile;
use App\Domains\Profile\Repositories\Interfaces\ProfileUserRepositoryInterface;
use App\Domains\User\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use \App\Domains\User\Models\User;
use Illuminate\Support\Facades\DB;

class ProfileUserRepository implements ProfileUserRepositoryInterface
{
    protected UserRepository $userRepository;

     public function __construct (UserRepository $userRepository)
     {
         $this->userRepository = $userRepository;
     }


    /**
     * @param int $profileId
     * @return int
     */
    public function countProfileUser(int $profileId): int
    {
        return DB::table('profile_user')->where('profile_id', $profileId)->count();
    }

    /**
     * @param int $userId
     * @return User|null
     */
    public function getProfilesByUser(int $userId): ?User
    {
        return $this->userRepository->findById($userId);
    }

    /**
     * @param ProfileUserCreateDTO $data
     * @return Profile
     */
    public function attachProfile(ProfileUserCreateDTO $data): void
    {
        $userModel = $this->userRepository->findById($data->user_id);
        $userModel->profiles()->attach($data->profiles_id);
    }

    /**
     * @param ProfileUserCreateDTO $data
     * @return void
     */
    public function detachProfile(ProfileUserCreateDTO $data): void
    {
        $userModel = $this->userRepository->findById($data->user_id);
        $userModel->profiles()->detach($data->profiles_id);
    }
}
