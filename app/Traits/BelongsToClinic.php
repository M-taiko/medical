<?php

namespace App\Traits;

use App\Models\Scopes\ClinicScope;

trait BelongsToClinic
{
    protected static function bootBelongsToClinic(): void
    {
        static::addGlobalScope(new ClinicScope);

        static::creating(function ($model) {
            if (empty($model->clinic_id) && auth()->check() && auth()->user()->clinic_id) {
                $model->clinic_id = auth()->user()->clinic_id;
            }
        });
    }

    public function clinic()
    {
        return $this->belongsTo(\App\Models\Clinic::class);
    }
}
