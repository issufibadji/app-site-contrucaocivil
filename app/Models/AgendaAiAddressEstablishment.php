<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class AgendaAiAddressEstablishment extends Model
{
    use HasFactory;

    protected $table = 'agendaai_address_establishments';
    protected $fillable = [
        'cep',
        'uf',
        'city',
        'street',
        'complement',
        'user_id',
    ];

    /**
     * Usuário dono deste endereço.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
