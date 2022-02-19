<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $casts = [
        'options'   => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    public function countries()
    {
        return $this->hasMany(Country::class);
    }

    public function states()
    {
        return $this->hasMany(State::class);
    }

    public function currencies()
    {
        return $this->hasMany(Currency::class);
    }
}
