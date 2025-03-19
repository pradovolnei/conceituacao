<?php

namespace App\Domains\User\Controllers;

use App\Domains\User\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
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
        $events = $this->userRepository->paginate();

        return response()->json($events);
    }
}
