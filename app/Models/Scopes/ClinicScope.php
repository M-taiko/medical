<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ClinicScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        // Super admin bypasses all tenant scoping
        if (auth()->check() && auth()->user()->isSuperAdmin()) {
            return;
        }

        // Authenticated clinic user — scope to their clinic
        if (auth()->check() && auth()->user()->clinic_id) {
            $builder->where($model->getTable() . '.clinic_id', auth()->user()->clinic_id);
            return;
        }

        // Unauthenticated or no clinic_id — prevent data leaks
        $builder->whereRaw('0 = 1');
    }
}
