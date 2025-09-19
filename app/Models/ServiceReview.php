<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'client_id',
        'professional_id',
        'rating',
        'comment',
    ];

    public function appointment()
    {
        return $this->belongsTo(AgendaAiAppointment::class);
    }

    public function client()
    {
        return $this->belongsTo(AgendaAiClient::class);
    }

    public function professional()
    {
        return $this->belongsTo(AgendaAiProfessional::class);
    }
}
