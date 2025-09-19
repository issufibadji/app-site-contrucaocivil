<?php

namespace App\Models;

use App\Models\User;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\AgendaAiEstablishment;
use App\Models\AgendaAiPhone;
use App\Models\AgendaAiService;
use App\Models\ServiceReview;
class AgendaAiProfessional extends Model
{
    use HasFactory, Uuid;

    protected $table = 'agendaai_professionals';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'user_id',
        'professions',
        'description',
    ];

    protected $casts = [
        'professions' => 'array',
    ];


    public function phones()
    {
        return $this->hasMany(AgendaAiPhone::class, 'professional_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function services()
    {
        return $this->belongsToMany(
            AgendaAiService::class,
            'agendaai_service_professional',
            'professional_id',
            'service_id',
            'id',
            'id'
        )->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(ServiceReview::class, 'professional_id', 'id');
    }

}
