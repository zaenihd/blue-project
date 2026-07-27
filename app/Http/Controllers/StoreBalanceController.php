<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\StoreBalanceResource;
use App\Interfaces\StoreBalanceRepositoryInterface;
use Illuminate\Http\Request;

class StoreBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    private StoreBalanceRepositoryInterface $storeBalanceRepository;
    public function __construct(StoreBalanceRepositoryInterface $storeBalanceRepository)
    {
        $this->storeBalanceRepository = $storeBalanceRepository;
    }

    public function index(Request $request)
    {
        try {
            $storeBalance = $this->storeBalanceRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelper::jsonResponse(true, "Data Balance Berhasil diambil", StoreBalanceResource::collection($storeBalance), 200);
            //code...
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
            //throw $th;
        }
    }

    public function getAllPaginated(Request $request)
    {
        $request = $request->validate([
            'search' => 'nullable|string',
            'rowPerPage' => 'required|integer'
        ]);
        try {
            $storeBalance = $this->storeBalanceRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['rowPerPage'],
            );

            return ResponseHelper::jsonResponse(true, "Data Balance Berhasil diambil", PaginateResource::make($storeBalance, StoreBalanceResource::class), 200);
            //code...
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
            $storeBalance = $this->storeBalanceRepository->getById($id);

            if (!$storeBalance) {
                return ResponseHelper::jsonResponse(false, "Data Balance Tidak ditemukan", null, 404);
            }
            return ResponseHelper::jsonResponse(true, "Data Balance ditemukan", new StoreBalanceResource($storeBalance), 200);
            //code...
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
            //throw $th;
        }
        //
    }

    public function credit(
        string $id,
        string $amount
    ){}

    public function debit(
        string $id,
        string $amount
    ){}
}
