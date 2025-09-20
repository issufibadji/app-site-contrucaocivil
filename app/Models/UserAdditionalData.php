<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAdditionalData extends Model
{
    use HasFactory;

    protected $table = 'user_additional_data';

    protected $fillable = [
        'user_id',
        'cpf',
        'rg',
        'birth_date',
        'phone',
        'secondary_phone',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];
}
