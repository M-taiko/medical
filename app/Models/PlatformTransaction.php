<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformTransaction extends Model
{
    protected $fillable = [
        'type',
        'reference_id',
        'reference_type',
        'amount',
        'transaction_date',
        'description',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount'           => 'decimal:2',
    ];

    public function reference()
    {
        return $this->morphTo();
    }
}
