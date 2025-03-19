<?php

namespace App\Domains\User\Controllers;

use App\Domains\User\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use App\Domains\User\Requests\UserCreateRequest;
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
        $userCreate = $this->userRepository->create($request->validated());
        return response()->json($userCreate, 201);
    }
}
