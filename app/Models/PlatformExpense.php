<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformExpense extends Model
{
    protected $fillable = [
        'category',
        'amount',
        'expense_date',
        'description',
        'receipt_path',
        'created_by',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount'       => 'decimal:2',
    ];

    public const CATEGORIES = [
        'server'      => 'Server & Hosting',
        'development' => 'Development',
        'marketing'   => 'Marketing',
        'maintenance' => 'Maintenance',
        'other'       => 'Other',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function transaction()
    {
        return $this->morphOne(PlatformTransaction::class, 'reference');
    }
}
