<?php

namespace App\Domains\Profile\Controllers;

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

    /**
     * @OA\Get(
     *     tags={"Perfis"},
     *     description="listagem de Perfis de um usuário",
     *     path="/api/profiles/users/{id}",
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
     *                   @OA\Property(property="name", type="string", example="Administrador"),
     *                   @OA\Property(property="created_at", type="string", example="2025-01-01"),
     *                   @OA\Property(property="profiles", type="Array", example=""),
     *                ),
     *             ),
     *          )
     *     ),
     * ),
     * @return JsonResponse
     */
    public function getProfilesByUser(int $id): JsonResponse
    {
        $userCreate = $this->profileUserRepository->getProfilesByUser($id);
        return response()->json($userCreate);
    }

    /**
     * @OA\Post(
     *  tags={"Perfis"},
     *  description="associar um usário a um perfil",
     *  path="/api/profiles/attach",
     *  security={{"sanctum":{}}},
     *  @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *             required={"name"},
     *             @OA\Property(property="user_id", type="integer", example="1"),
     *             @OA\Property(property="profiles_id", type="Array", example="[1, 2]"),
     *          )
     *      ),
     *  ),
     *  @OA\Response(
     *    response=201,
     *    description="",
     *  ),
     * )
     * @param  ProfileUserCreateRequest  $request
     * @return JsonResponse
     */
    public function attachProfile(ProfileUserCreateRequest $request): JsonResponse
    {
        $dataDataTransfer = ProfileUserCreateDTO::create($request->validated());
        $userCreate = $this->profileUserRepository->attachProfile($dataDataTransfer);
        return response()->json($userCreate, 201);
    }

    /**
     * @OA\Post(
     *  tags={"Perfis"},
     *  description="desassociar um usário de um perfil",
     *  path="/api/profiles/detach",
     *  security={{"sanctum":{}}},
     *  @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *             required={"name"},
     *             @OA\Property(property="user_id", type="integer", example="1"),
     *             @OA\Property(property="profiles_id", type="Array", example="[1, 2]"),
     *          )
     *      ),
     *  ),
     *  @OA\Response(
     *    response=201,
     *    description="",
     *  ),
     * )
     * @param  ProfileUserCreateRequest  $request
     * @return JsonResponse
     */
    public function detachProfile(ProfileUserCreateRequest $request): JsonResponse
    {
        $dataDataTransfer = ProfileUserCreateDTO::create($request->validated());
        $userCreate = $this->profileUserRepository->detachProfile($dataDataTransfer);
        return response()->json($userCreate, 201);
    }
}
