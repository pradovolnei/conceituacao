<?php

namespace App\Domains\User\Controllers;

use App\Domains\User\DataTransfers\UserCreateDTO;
use App\Domains\User\DataTransfers\UserUpdateDTO;
use App\Domains\User\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use App\Domains\User\Requests\UserCreateRequest;
use App\Domains\User\Requests\UserUpdateRequest;
use Illuminate\Http\JsonResponse;

use App\Core\Controllers\Controller;

class UserController extends Controller
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(): JsonResponse
    {
        $users = $this->userRepository->paginate();

        return response()->json($users);
    }

    public function store(UserCreateRequest $request): JsonResponse
    {
        $dataDataTransfer = UserCreateDTO::create($request->validated());
        $userCreate = $this->userRepository->create($dataDataTransfer);
        return response()->json($userCreate, 201);
    }

    public function update(UserUpdateRequest $request, int $id): JsonResponse
    {
        $dataDataTransfer = UserUpdateDTO::create($request->validated());
        $userUpdate = $this->userRepository->update($dataDataTransfer, $id);
        return response()->json($userUpdate);
    }

    public function destroy(int $id): JsonResponse
    {
        $userDelete = $this->userRepository->delete($id);
        return response()->json($userDelete);
    }
}
