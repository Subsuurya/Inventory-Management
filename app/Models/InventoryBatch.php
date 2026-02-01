<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'supplier_id',
        'purchase_order_id',
        'batch_number',
        'quantity_received',
        'quantity_remaining',
        'received_date',
    ];

    protected function casts(): array
    {
        return [
            'quantity_received' => 'decimal:2',
            'quantity_remaining' => 'decimal:2',
            'received_date' => 'date',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function salesItems(): HasMany
    {
        return $this->hasMany(SalesItem::class, 'inventory_batch_id');
    }

    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }
}
