<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use App\Models\AgendaAiService;
use App\Models\AgendaAiMessageSetting;
use App\Models\AgendaAiPhone;

class AgendaAiEstablishment extends Model
{
    use HasFactory;
    use Uuid;

    protected $table = 'agendaai_establishments';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'name',
        'link',
        'manual_chat_link',
        'descrition',
        'image',
        'user_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->name = trim($model->name);
            $model->manual_chat_link = Str::slug($model->name);

            if (empty($model->link)) {
                $model->link = 'https://tchamservices.com/chat/' . $model->manual_chat_link;
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function services()
    {
        return $this->hasMany(AgendaAiService::class, 'establishment_id');
    }


    public function messageSettings()
    {
        return $this->hasMany(AgendaAiMessageSetting::class, 'establishment_id');
    }

    public function phones()
    {
        return $this->hasMany(AgendaAiPhone::class, 'establishment_id');
    }
   public function appointments()
    {
        return $this->hasMany(\App\Models\AgendaAiAppointment::class, 'establishment_id');
    }

    public function professionals()
    {
        return $this->hasMany(\App\Models\AgendaAiProfessional::class, 'establishment_id');
    }

    public function clients()
    {
        return $this->hasMany(\App\Models\AgendaAiClient::class, 'establishment_id');
    }

    public function payments()
    {
        return $this->hasMany(\App\Models\AgendaAiPayment::class, 'establishment_id');
    }

    public function address()
    {
        return $this->hasOne(\App\Models\AgendaAiAddressEstablishment::class, 'establishment_id');
    }

    public function phone()
    {
        return $this->hasOne(\App\Models\AgendaAiPhone::class, 'establishment_id');
    }


}
