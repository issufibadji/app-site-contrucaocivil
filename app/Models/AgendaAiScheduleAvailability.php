<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\AgendaAiAppointment;

class AgendaAiScheduleAvailability extends Model
{
    use HasFactory;

    protected $table = 'agendaai_schedule_availabilities';

    protected $fillable = [
        'schedule_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    public function schedule()
    {
        return $this->belongsTo(AgendaAiSchedule::class, 'schedule_id', 'id');
    }

    public function appointments()
    {
        return $this->hasMany(AgendaAiAppointment::class, 'schedule_availability_id');
    }
}
