<?php

namespace App\Repositories;

use App\Interfaces\StoreBalanceRepositoryInterface;
use App\Models\StoreBalance;
use Exception;
use Illuminate\Support\Facades\DB;

class StoreBalanceRepository implements StoreBalanceRepositoryInterface
{

    public function getAll(
        ?string $search,
        ?int $limit,
        bool $exceute
    ) {
        $query = StoreBalance::where(function ($query) use ($search) {
            if ($search) {
                $query->search($search);
            }
        });

        if ($limit) {
            $query->take($limit);
        }

        if ($exceute) {
            return $query->get();
        }

        return $query;
    }

    public function getAllPaginated(
        ?string $search,
        ?int $rowPerPage
    ) {
        $query = $this->getAll($search, null, false);

        return $query->paginate($rowPerPage);
    }

    public function getById(
        string $id
    ) {
        $query = StoreBalance::where('id', $id);

        return $query->first();
    }

     public function credit(
        string $id,
        string $amount
    ){
        DB::beginTransaction();

        try {
            $storeBalance = StoreBalance::find($id);
            $storeBalance->balance = bcadd($storeBalance->balance, $amount, 2);
            $storeBalance->save();

            DB::commit();

            return $storeBalance;

            //code...
        } catch (\Exception $e) {
            //throw $th;
            DB::rollBack();
            return new Exception($e->getMessage());
        }
    }
     public function debit(
        string $id,
        string $amount
    ){
        DB::beginTransaction();

        try {
            $storeBalance = StoreBalance::find($id);
            if(bccomp($storeBalance->balance, $amount, 2) < 0){
                return new Exception('Saldo tidak mencukupi');
            }
            $storeBalance->balance = bcsub($storeBalance->balance, $amount, 2);
            $storeBalance->save();

            DB::commit();

            return $storeBalance;

            //code...
        } catch (\Exception $e) {
            //throw $th;
            DB::rollBack();
            return new Exception($e->getMessage());
        }
    }

}
