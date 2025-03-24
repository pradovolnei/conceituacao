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

    /**
     * @OA\Get(
     *     tags={"Usuários"},
     *     description="listagem de Usuários",
     *     path="/api/users",
     *     security={{"sanctum":{}}},
     *    @OA\SecurityScheme(
     *      securityScheme="bearerAuth",
     *      in="header",
     *      name="bearerAuth",
     *      type="http",
     *      scheme="bearer",
     *      bearerFormat="JWT",
     * ),
     *     @OA\Response(
     *          response=200,
     *          description="",
     *          @OA\JsonContent(
     *              @OA\Property(property="current_page", type="number", example="5"),
     *              @OA\Property(property="total", type="number", example="5"),
     *              @OA\Property(property="per_page", type="number", example="15"),
     *              @OA\Property(
     *                property="data",
     *                type="array",
     *                   @OA\Items(
     *                    @OA\Property(property="id", type="integer", example="1"),
     *                   @OA\Property(property="name", type="string", example="Admin User"),
     *                   @OA\Property(property="email", type="string", example="email@email"),
     *                   @OA\Property(property="created_at", type="string", example="2025-01-01"),
     *                ),
     *             ),
     *          )
     *     ),
     * ),
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $users = $this->userRepository->paginate();

        return response()->json($users);
    }

    /**
     * @OA\Post(
     *  tags={"Usuários"},
     *  description="registra um novo usuário",
     *  path="/api/users",
     *  security={{"sanctum":{}}},
     *  @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string", example="Admin"),
     *             @OA\Property(property="email", type="string", example="admin@admin.com.br"),
     *             @OA\Property(property="password", type="string", example="admin"),
     *          )
     *      ),
     *  ),
     *  @OA\Response(
     *    response=201,
     *    description="",
     *  ),
     * )
     * @param  UserCreateRequest  $request
     * @return JsonResponse
     */
    public function store(UserCreateRequest $request): JsonResponse
    {
        $dataDataTransfer = UserCreateDTO::create($request->validated());
        $userCreate = $this->userRepository->create($dataDataTransfer);
        return response()->json($userCreate, 201);
    }

    /**
     * @OA\Put(
     *  tags={"Usuários"},
     *  description="atualiza um usuário",
     *  path="/api/users/{id}",
     *  security={{"sanctum":{}}},
     * @OA\Parameter(
     *    name="id",
     *    in="path",
     *    required=true,
     *    description="",
     *    @OA\Schema(
     *       type="string"
     *    ),
     * ),
     *   @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *            required={"name","email","password"},
     *             @OA\Property(property="name", type="string", example="Admin"),
     *             @OA\Property(property="email", type="string", example="admin@admin.com.br"),
     *          )
     *      ),
     *  ),
     *  @OA\Response(
     *    response=404,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="usuário não encontrado"),
     *    )
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="",
     *  ),
     * )
     * @param UserUpdateRequest $request
     * @param int $id
     * @return mixed
     */
    public function update(UserUpdateRequest $request, int $id): JsonResponse
    {
        $dataDataTransfer = UserUpdateDTO::create($request->validated());
        $userUpdate = $this->userRepository->update($dataDataTransfer, $id);
        return response()->json($userUpdate);
    }

    /**
     * @OA\Delete(
     *  tags={"Usuários"},
     *  description="remove um usuário",
     *  path="/api/users/{id}",
     *  security={{"sanctum":{}}},
     * @OA\Parameter(
     *    name="id",
     *    in="path",
     *    required=true,
     *    description="",
     *    @OA\Schema(
     *       type="string"
     *    ),
     * ),
     *  @OA\Response(
     *    response=200,
     *    description="",
     *  ),
     *  @OA\Response(
     *    response=404,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="usuário não encontrado"),
     *    )
     *  ),
     * )
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $userDelete = $this->userRepository->delete($id);
        return response()->json($userDelete);
    }
}
