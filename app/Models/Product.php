<?php

namespace App\Models;

use App\Traits\BelongsToClinic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes, BelongsToClinic;

    protected $fillable = [
        'clinic_id',
        'name',
        'sku',
        'unit',
        'stock_quantity',
        'low_stock_threshold',
        'cost_price',
        'expiry_date',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'expiry_date' => 'date',
    ];

    public function movements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
