<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgendaAiRequest extends Model
{
    use HasFactory;

    protected $table = 'agendaai_requests';

    protected $fillable = [
        'client_id',
        'category_id',
        'service_id',
        'professional_id',
        'schedule_availability_id',
        'duration',
        'price',
        'scheduled_at',
        'address',
        'photo_path',
        'priority',
        'description',
        'status',
    ];

    protected $casts = [
        'duration' => 'integer',
        'price' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(AgendaAiClient::class, 'client_id');
    }

    public function category()
    {
        return $this->belongsTo(AgendaAiServiceCategory::class, 'category_id');
    }

    public function service()
    {
        // relate to the service using its numeric id as owner key
        return $this->belongsTo(AgendaAiService::class, 'service_id', 'id');
    }

    public function professional()
    {
        // professionals also use uuid as primary key, but requests keep the numeric id
        return $this->belongsTo(AgendaAiProfessional::class, 'professional_id', 'id');
    }

    public function availability()
    {
        return $this->belongsTo(AgendaAiScheduleAvailability::class, 'schedule_availability_id');
    }
}
