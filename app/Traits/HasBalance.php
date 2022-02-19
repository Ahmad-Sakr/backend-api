<?php


namespace App\Traits;


use App\Models\Balance;

trait HasBalance
{
    public function balances()
    {
        return $this->morphMany(Balance::class,'balanceable');
    }

//    public function log($action, $description = '', $options = [])
//    {
//        return $this->logs()->create([
//            'user_id'        =>  (auth()->check() ? auth()->user()->id : null),
//            'action'         =>  $action,
//            'description'    =>  $description,
//            'options'        =>  $options,
//        ]);
//    }
}
