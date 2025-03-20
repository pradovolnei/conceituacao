<?php

namespace App\Domains\Profile\Controllers;

use App\Domains\Profile\DataTransfers\ProfileDTO;
use App\Domains\Profile\Repositories\Interfaces\ProfileRepositoryInterface as ProfileRepository;
use App\Domains\Profile\Requests\ProfileCreateRequest;
use Illuminate\Http\JsonResponse;

use App\Core\Controllers\Controller;

class ProfileController extends Controller
{
    protected ProfileRepository $profileRepository;

    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function index(): JsonResponse
    {
        $users = $this->profileRepository->paginate();

        return response()->json($users);
    }

    public function store(ProfileCreateRequest $request): JsonResponse
    {
        $dataDataTransfer = ProfileDTO::create($request->validated());
        $userCreate = $this->profileRepository->create($dataDataTransfer);
        return response()->json($userCreate, 201);
    }

    public function update(ProfileCreateRequest $request, int $id): JsonResponse
    {
        $dataDataTransfer = ProfileDTO::create($request->validated());
        $userUpdate = $this->profileRepository->update($dataDataTransfer, $id);
        return response()->json($userUpdate);
    }

    public function destroy(int $id): JsonResponse
    {
        $userDelete = $this->profileRepository->delete($id);
        return response()->json($userDelete);
    }
}
