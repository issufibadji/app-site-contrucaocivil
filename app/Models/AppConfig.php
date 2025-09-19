<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; // ✅ ESSA LINHA É NECESSÁRIA
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AppConfig extends Model
{
    protected $fillable = [
        'key',
        'value',
        'description',
        'path_archive',
        'extension',
        'required',
        'uuid',
    ];

    protected $casts = [
        'required' => 'boolean',
    ];

    protected $appends = [
        'media_url',
    ];

    public $timestamps = false;

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }

            $timestamp = DB::raw('CURRENT_TIMESTAMP');
            $model->created_at = $timestamp;
            $model->updated_at = $timestamp;
        });

        static::updating(function ($model) {
            $model->updated_at = DB::raw('CURRENT_TIMESTAMP');
        });
    }

    public function getMediaUrlAttribute(): ?string
    {
        return $this->path_archive
            ? Storage::disk('public')->url($this->path_archive)
            : null;
    }
}
