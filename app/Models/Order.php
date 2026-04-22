<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
    'amount_paid',
    'status',
    'notes',
    'lead_source',
])]
class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    /** @var list<string> */
    protected $appends = ['remaining_balance', 'payment_status'];

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
    /**
     * @return Attribute<string, never>
     */
    protected function remainingBalance(): Attribute
    {
        return Attribute::get(
            fn (): string => number_format($this->total_price - $this->amount_paid, 2, '.', '')
        );
    }

    /**
     * @return Attribute<string, never>
     */
    protected function paymentStatus(): Attribute
    {
        return Attribute::get(function (): string {
            $paid = (float) $this->amount_paid;
            $total = (float) $this->total_price;

            if (round($paid - $total, 2) >= 0) {
                return 'paid';
            }

            if (round($paid, 2) > 0) {
                return 'partial';
            }

            return 'unpaid';
        });
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'total_price' => 'decimal:2',
            'amount_paid' => 'decimal:2',
            'quantity' => 'integer',
        ];
    }
}
