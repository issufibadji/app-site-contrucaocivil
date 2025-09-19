<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;
use App\Models\AgendaAiAddressEstablishment;
use App\Models\AgendaAiPhone;
use App\Models\AgendaAiService;
use App\Models\AgendaAiProfessional;
use App\Models\AgendaAiClient;

class User extends Authenticatable implements AuditableContract
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'active_2fa',
        'google2fa_secret',
        'confirmed_2fa',
        'active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'google2fa_secret' => 'string',
            'active' => 'boolean',
            'active_2fa' => 'boolean',
            'confirmed_2fa' => 'boolean',
            'password' => 'hashed',
        ];
    }

    /**
     * Endereços que pertencem a este usuário.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->hasMany(AgendaAiAddressEstablishment::class);
    }

    /**
     * Telefones que pertencem a este usuário.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function phones()
    {
        return $this->hasMany(AgendaAiPhone::class);
    }

    /**
     * Serviços oferecidos ou associados a este usuário.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function services()
    {
        return $this->hasMany(AgendaAiService::class);
    }

    /**
     * Perfil profissional relacionado a este usuário.
     */
    public function professional()
    {
        return $this->hasOne(AgendaAiProfessional::class, 'user_id');
    }

    /**
     * Perfil de cliente relacionado a este usuário.
     */
    public function client()
    {
        return $this->hasOne(AgendaAiClient::class, 'user_id');
    }
}
