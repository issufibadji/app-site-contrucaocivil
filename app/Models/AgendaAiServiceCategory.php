<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaAiServiceCategory extends Model
{
    use HasFactory;

    protected $table = 'agendaai_service_categories';
    // primaryKey, incrementing e keyType ficam todos como default (id, true, int)
    protected $fillable = ['uuid','name'];

    public function services()
    {
        return $this->hasMany(AgendaAiService::class,'category_id','id');
    }
}
