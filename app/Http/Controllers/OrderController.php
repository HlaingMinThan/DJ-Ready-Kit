<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(Request $request): Response
    {
        $status = $request->query('status');

        $orders = Order::query()
            ->with('creator:id,name')
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('orders/Index', [
            'orders' => $orders,
            'statuses' => collect(OrderStatus::cases())->map(fn (OrderStatus $s) => [
                'value' => $s->value,
                'label' => $s->label(),
                'color' => $s->color(),
            ]),
            'currentStatus' => $status,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('orders/Create');
    }

    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $order = new Order($request->validated());
        $order->created_by = $request->user()->id;
        $order->save();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Order created. Code: :code', ['code' => $order->order_code])]);

        return to_route('orders.show', $order);
    }

    public function show(Order $order): Response
    {
        $order->load('creator:id,name');

        return Inertia::render('orders/Show', [
            'order' => $order,
            'statuses' => collect(OrderStatus::cases())->map(fn (OrderStatus $s) => [
                'value' => $s->value,
                'label' => $s->label(),
                'color' => $s->color(),
            ]),
        ]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', new Enum(OrderStatus::class)],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $order->update($validated);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Order updated.')]);

        return to_route('orders.show', $order);
    }
}
