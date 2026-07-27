<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\UserResource;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{

    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {

        $this->userRepository = $userRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $users = $this->userRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelper::jsonResponse(true, "Data User Berhasil diambil", UserResource::collection($users), 200);
            //code...
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
            //throw $th;
        }
        //
    }

    public function getAllPaginated(Request $request)
    {
        $request = $request->validate([
            'search' => 'nullable|string',
            'rowPerPage' => 'required|integer'
        ]);
        try {
            $users = $this->userRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['rowPerPage'],
            );

            return ResponseHelper::jsonResponse(true, "Data User Berhasil diambil", PaginateResource::make($users, UserResource::class), 200);
            //code...
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
            //throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        $request = $request->validated();
        try {
            $user = $this->userRepository->create(
                $request
            );
            return ResponseHelper::jsonResponse(true, "Data User Berhasil ditambahkan", new UserResource($user), 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);

            //throw $th;
        }

        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        try {
            $user = $this->userRepository->getById(
                $id
            );
            if (!$user) {
                return ResponseHelper::jsonResponse(true, "Data User tidak ditemukan", null, 404);
            }

            return ResponseHelper::jsonResponse(true, "Data User Berhasil diambil", new UserResource($user), 200);
            //code...
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
            //throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, string $id)
    {
        //
        $request = $request->validated();
        try {
            $user = $this->userRepository->getById(
                $id
            );

            if (!$user) {
                return ResponseHelper::jsonResponse(true, "Data User tidak ditemukan", null, 404);
            }
            $user = $this->userRepository->update(
                $id,
                $request
            );
            return ResponseHelper::jsonResponse(true, "Data User Berhasil di update", new UserResource($user), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);

            //throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        try {
            $user = $this->userRepository->getById(
                $id
            );

            if (!$user) {
                return ResponseHelper::jsonResponse(true, "Data User tidak ditemukan", null, 404);
            }
            $user = $this->userRepository->delete(
                $id,
            );
            return ResponseHelper::jsonResponse(true, "Data User Berhasil di hapus", new UserResource($user), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);

            //throw $th;
        }
    }
}
