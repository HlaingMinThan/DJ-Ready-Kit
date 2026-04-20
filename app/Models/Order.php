<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable([
    'customer_name',
    'customer_phone',
    'customer_address',
    'item_description',
    'quantity',
    'total_price',
    'status',
    'notes',
])]
class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (empty($order->order_code)) {
                $order->order_code = 'DJ-'.strtoupper(Str::random(8));
            }
        });
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'total_price' => 'decimal:2',
            'quantity' => 'integer',
        ];
    }
}
