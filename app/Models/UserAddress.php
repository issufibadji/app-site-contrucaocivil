<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'zip_code',
        'street',
        'number',
        'complement',
        'district',
        'city',
        'state',
        'country',
        'is_international',
    ];

    protected $casts = [
        'is_international' => 'boolean',
    ];
}
