<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product\Order;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'expiry',
        'status',
        'user_id',
        'unlimited',
        // add all other fields
    ];

    public function products()
    {
        return $this->belongsToMany('App\Models\Product\Product');
    }

    public function tests()
    {
        return $this->belongsToMany('App\Models\Test\Test');
    }

    public function count()
    {
        return Order::where('txn_id',$this->code)->count();
    }
}
