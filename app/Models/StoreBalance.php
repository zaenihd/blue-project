<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreBalance extends Model
{
    //
    use UUID, HasFactory;

    protected $fillable = ['store_id', 'balance'];

    protected $casts = [
        'balance' => 'decimal:2'
    ];

    public function scopeSearch($query, $search){
        return $query->whereHas('store', function ($q) use ($search){
            $q->where('name', 'like', '%' . $search . '%');
        }
        );
    }

    public function store (){
        return $this->belongsTo(Store::class);
    }

    public function storebalanceHistories(){
        return $this->hasMany(StoreBalanceHistory::class, 'store_balance_id');
    }

    public function withdrawals(){
        return $this->hasMany(Withdrawal::class, 'store_balance_id');
    }

}
