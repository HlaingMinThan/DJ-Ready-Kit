<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
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

        $recentOrders = Order::with('creator:id,name')
            ->latest()
            ->take(5)
            ->get();

        $paymentSummary = [
            'total_revenue' => (float) Order::sum('total_price'),
            'total_collected' => (float) Order::sum('amount_paid'),
            'total_outstanding' => (float) Order::sum(DB::raw('total_price - amount_paid')),
        ];

        return Inertia::render('Dashboard', [
            'statusCounts' => $statusCounts,
            'recentOrders' => $recentOrders,
            'paymentSummary' => $paymentSummary,
        ]);
    }
}
