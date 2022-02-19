<?php

namespace App\Models;

use App\Traits\HasBalance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Client extends Model
{
    use HasFactory, Notifiable, HasBalance;

    protected $guarded = [];
    public $timestamps = false;

    public $casts = [
        'custom_fields'   => 'array'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
