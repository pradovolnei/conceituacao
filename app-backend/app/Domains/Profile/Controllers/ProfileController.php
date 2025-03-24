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


    /**
     * @OA\Get(
     *     tags={"Perfis"},
     *     description="listagem de Perfis",
     *     path="/api/profiles",
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
     *                ),
     *             ),
     *          )
     *     ),
     * ),
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $users = $this->profileRepository->paginate();

        return response()->json($users);
    }

    /**
     * @OA\Post(
     *  tags={"Perfis"},
     *  description="registra um novo perfil",
     *  path="/api/profiles",
     *  security={{"sanctum":{}}},
     *  @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Administrador"),
     *          )
     *      ),
     *  ),
     *  @OA\Response(
     *    response=201,
     *    description="",
     *  ),
     * )
     * @param  ProfileCreateRequest  $request
     * @return JsonResponse
     */
    public function store(ProfileCreateRequest $request): JsonResponse
    {
        $dataDataTransfer = ProfileDTO::create($request->validated());
        $userCreate = $this->profileRepository->create($dataDataTransfer);
        return response()->json($userCreate, 201);
    }

    /**
     * @OA\Put(
     *  tags={"Perfis"},
     *  description="atualiza um Perfil",
     *  path="/api/profiles/{id}",
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
     *            required={"name"},
     *           @OA\Property(property="name", type="string", example="Administrador"),
     *          )
     *      ),
     *  ),
     *  @OA\Response(
     *    response=404,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="perfil não encontrado"),
     *    )
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="",
     *  ),
     * )
     * @param ProfileCreateRequest $request
     * @param int $id
     * @return mixed
     */
    public function update(ProfileCreateRequest $request, int $id): JsonResponse
    {
        $dataDataTransfer = ProfileDTO::create($request->validated());
        $userUpdate = $this->profileRepository->update($dataDataTransfer, $id);
        return response()->json($userUpdate);
    }

    /**
     * @OA\Delete(
     *  tags={"Perfis"},
     *  description="remove um perfil",
     *  path="/api/profiles/{id}",
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
     *       @OA\Property(property="message", type="string", example="Perfil não encontrado"),
     *    )
     *  ),
     * )
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $userDelete = $this->profileRepository->delete($id);
        return response()->json($userDelete);
    }
}
