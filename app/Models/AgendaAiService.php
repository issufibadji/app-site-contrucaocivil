<?php
// app/Models/AgendaAiService.php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AgendaAiServiceCategory;  // ← importe a categoria

class AgendaAiService extends Model
{
    use HasFactory, Uuid;

    protected $table      = 'agendaai_services';
    protected $primaryKey = 'uuid';
    public $incrementing  = false;
    protected $keyType    = 'string';

    protected $fillable = [
        'uuid',
        'name',
        'duration_min',
        'price',
        'user_id',
        'category_id',    // ← inclua aqui
        'image',
        'descrition',
    ];

    public function professionals()
    {
        return $this->belongsToMany(
            AgendaAiProfessional::class,
            'agendaai_service_professional',
            'service_id',
            'professional_id',
            'id',
            'id'
        )->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Categoria deste serviço.
     */
    public function category()
    {
        return $this->belongsTo(
            AgendaAiServiceCategory::class,
            'category_id',  // FK no services
            'id'            // PK na categories
        );
    }
}
