<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreStoreRequest;
use App\Http\Requests\StoreUpdateRequest;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\StoreResource;
use App\Interfaces\StoreRepositoryInterface;
use Illuminate\Http\Request;
use Laravel\Mcp\Response;

class StoreController extends Controller
{

    private StoreRepositoryInterface $storeRepository;

    public function __construct(StoreRepositoryInterface $storeRepository)
    {
        $this->storeRepository = $storeRepository;
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $stores = $this->storeRepository->getAll(
                $request->search,
                $request->isVerified,
                $request->limit,
                true
            );

            return ResponseHelper::jsonResponse(true, "Data Toko Berhasil diambil", StoreResource::collection($stores), 200);
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
            $stores = $this->storeRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['isVerified'] ?? null,
                $request['rowPerPage'],
            );

            return ResponseHelper::jsonResponse(true, "Data toko Berhasil diambil", PaginateResource::make($stores, StoreResource::class), 200);
            //code...
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
            //throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $store = $this->storeRepository->create(
                $request
            );
            return ResponseHelper::jsonResponse(true, "Data Toko Berhasil ditambahkan", new StoreResource($store), 201);
        } catch (\Throwable $th) {
            //throw $th;
            return ResponseHelper::jsonResponse(false, $th->getMessage(), null, 500);
        }
        //

    }

    public function updateVerifiedStatus(string $id)
    {
        try {
            //code...
            $store = $this->storeRepository->getById(
                $id
            );
            if (!$store) {
                return ResponseHelper::jsonResponse(false, "Data Toko Tidak ditemukan", null, 404);
            }
            $store = $this->storeRepository->updateVerifiedStatus(
                $id,
                true
            );

            return ResponseHelper::jsonResponse(true, "Data Toko Berhasil di verifikasi", new StoreResource($store), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);

            //throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {

            $store = $this->storeRepository->getById(
                $id
            );

            if (!$store) {
                return ResponseHelper::jsonResponse(false, "Data Toko Tidak ditemukan", null, 404);
            }
            return ResponseHelper::jsonResponse(true, "Data Toko ditemukan", new StoreResource($store), 200);
        }
        //
        catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateRequest $request, string $id)
    {
        $request = $request->validated();

        try {
            $store = $this->storeRepository->getById(
                $id
            );

            if (!$store) {
                return ResponseHelper::jsonResponse(false, "Data Toko Tidak ditemukan", null, 404);
            }

            $store = $this->storeRepository->update(
                $id,
                $request
            );
            return ResponseHelper::jsonResponse(true, "Data Toko berhasil di update", new StoreResource($store), 200);

            //code...
        } catch (\Exception $e) {
            //throw $th;
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $store = $this->storeRepository->getByid($id);

            if (!$store) {
                return ResponseHelper::jsonResponse(false, "Data Toko tidak ditemukan", null, 404);
            }
            $store =  $this->storeRepository->delete($id);
            return ResponseHelper::jsonResponse(true, "Data Toko berhasil di hapus", new StoreResource($store), 200);
            //code...
        } catch (\Exception $e) {
            //throw $th;
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
        //
    }
}
