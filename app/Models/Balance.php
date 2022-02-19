<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;

    public function balanceable()
    {
        return $this->morphTo();
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
