<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\AgendaAiProfessional;
use App\Models\User;

class AgendaAiPhone extends Model
{
    use HasFactory;

    protected $table = 'agendaai_phones';
    protected $fillable = [
        'ddi',
        'ddd',
        'phone',
        'user_id',
    ];

    /**
     * Usuário dono deste telefone.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

}
