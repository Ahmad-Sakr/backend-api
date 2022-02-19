<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $casts = [
        'options'   => 'array'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}
