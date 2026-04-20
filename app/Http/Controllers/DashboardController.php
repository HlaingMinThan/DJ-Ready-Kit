<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        $counts = Order::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusCounts = collect(OrderStatus::cases())->map(fn (OrderStatus $s) => [
            'value' => $s->value,
            'label' => $s->label(),
            'color' => $s->color(),
            'count' => $counts->get($s->value, 0),
        ]);

        return Inertia::render('Dashboard', [
            'statusCounts' => $statusCounts,
        ]);
    }
}
