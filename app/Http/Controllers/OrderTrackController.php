<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderTrackController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Track');
    }

    public function show(Request $request): Response
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $order = Order::where('order_code', $request->query('code'))->first();

        return Inertia::render('TrackResult', [
            'order' => $order ? [
                'order_code' => $order->order_code,
                'customer_name' => $order->customer_name,
                'item_description' => $order->item_description,
                'quantity' => $order->quantity,
                'total_price' => $order->total_price,
                'status' => $order->status->value,
                'status_label' => $order->status->label(),
                'status_color' => $order->status->color(),
                'amount_paid' => $order->amount_paid,
                'remaining_balance' => $order->remaining_balance,
                'payment_status' => $order->payment_status,
                'created_at' => $order->created_at->toDateTimeString(),
            ] : null,
        ]);
    }
}
