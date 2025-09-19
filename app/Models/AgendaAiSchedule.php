<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\AgendaAiProfessional;
use App\Models\AgendaAiScheduleAvailability;

class AgendaAiSchedule extends Model
{
    use HasFactory;

    protected $table = 'agendaai_schedules';
    protected $fillable = [
        'schedule',
        'professional_id',
    ];

    public function professional()
    {
        return $this->belongsTo(AgendaAiProfessional::class, 'professional_id', 'id');
    }

    public function availabilities()
    {
        return $this->hasMany(AgendaAiScheduleAvailability::class, 'schedule_id');
    }
}
