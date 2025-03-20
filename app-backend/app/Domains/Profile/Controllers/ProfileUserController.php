<?php

namespace App\Domains\Profile\Controllers;

use App\Domains\Profile\DataTransfers\ProfileDTO;
use App\Domains\Profile\DataTransfers\ProfileUserCreateDTO;
use App\Domains\Profile\Repositories\Interfaces\ProfileUserRepositoryInterface as ProfileUserRepository;
use App\Domains\Profile\Requests\ProfileUserCreateRequest;
use Illuminate\Http\JsonResponse;
use App\Core\Controllers\Controller;

class ProfileUserController extends Controller
{
    protected ProfileUserRepository $profileUserRepository;

    public function __construct(ProfileUserRepository $profileUserRepository)
    {
        $this->profileUserRepository = $profileUserRepository;
    }

    public function getProfilesByUser(int $id): JsonResponse
    {
        $userCreate = $this->profileUserRepository->getProfilesByUser($id);
        return response()->json($userCreate);
    }

    public function attachProfile(ProfileUserCreateRequest $request): JsonResponse
    {
        $dataDataTransfer = ProfileUserCreateDTO::create($request->validated());
        $userCreate = $this->profileUserRepository->attachProfile($dataDataTransfer);
        return response()->json($userCreate, 201);
    }

    public function detachProfile(ProfileUserCreateRequest $request): JsonResponse
    {
        $dataDataTransfer = ProfileUserCreateDTO::create($request->validated());
        $userCreate = $this->profileUserRepository->detachProfile($dataDataTransfer);
        return response()->json($userCreate, 201);
    }
}
