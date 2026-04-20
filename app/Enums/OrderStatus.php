<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case OnTheWay = 'on_the_way';
    case Delivered = 'delivered';
    case Returned = 'returned';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Approved => 'Approved',
            self::OnTheWay => 'On the Way',
            self::Delivered => 'Delivered',
            self::Returned => 'Returned',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'yellow',
            self::Approved => 'blue',
            self::OnTheWay => 'indigo',
            self::Delivered => 'green',
            self::Returned => 'red',
        };
    }
}
