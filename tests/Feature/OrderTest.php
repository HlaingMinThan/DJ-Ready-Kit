<?php

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;

test('guest cannot access orders list', function () {
    $this->get(route('orders.index'))->assertRedirect(route('login'));
});

test('authenticated user can view orders list', function () {
    $user = User::factory()->create();
    Order::factory()->count(3)->create(['created_by' => $user->id]);

    $this->actingAs($user)
        ->get(route('orders.index'))
        ->assertOk();
});

test('authenticated user can filter orders by status', function () {
    $user = User::factory()->create();
    Order::factory()->create(['created_by' => $user->id, 'status' => OrderStatus::Pending]);
    Order::factory()->approved()->create(['created_by' => $user->id]);

    $this->actingAs($user)
        ->get(route('orders.index', ['status' => 'approved']))
        ->assertOk();
});

test('authenticated user can view create order form', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('orders.create'))
        ->assertOk();
});

test('authenticated user can create an order', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('orders.store'), [
            'customer_name' => 'John Doe',
            'customer_phone' => '09123456789',
            'customer_address' => '123 Main St, Yangon',
            'item_description' => 'DJ Stick Pro',
            'quantity' => 2,
            'total_price' => 50000,
            'notes' => 'Rush order',
        ])
        ->assertRedirect();

    $order = Order::first();
    expect($order)->not->toBeNull();
    expect($order->customer_name)->toBe('John Doe');
    expect($order->order_code)->toStartWith('DJ-');
    expect($order->status)->toBe(OrderStatus::Pending);
    expect($order->created_by)->toBe($user->id);
});

test('order creation validates required fields', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('orders.store'), [])
        ->assertSessionHasErrors([
            'customer_name',
            'customer_phone',
            'customer_address',
            'item_description',
            'quantity',
            'total_price',
        ]);
});

test('authenticated user can view an order', function () {
    $user = User::factory()->create();
    $order = Order::factory()->create(['created_by' => $user->id]);

    $this->actingAs($user)
        ->get(route('orders.show', $order))
        ->assertOk();
});

test('authenticated user can update order status', function () {
    $user = User::factory()->create();
    $order = Order::factory()->create(['created_by' => $user->id]);

    $this->actingAs($user)
        ->patch(route('orders.update', $order), [
            'status' => 'approved',
        ])
        ->assertRedirect();

    expect($order->refresh()->status)->toBe(OrderStatus::Approved);
});

test('order status update validates enum', function () {
    $user = User::factory()->create();
    $order = Order::factory()->create(['created_by' => $user->id]);

    $this->actingAs($user)
        ->patch(route('orders.update', $order), [
            'status' => 'invalid_status',
        ])
        ->assertSessionHasErrors('status');
});

test('public user can access tracking page', function () {
    $this->get(route('track'))->assertOk();
});

test('public user can track an order by code', function () {
    $order = Order::factory()->create();

    $this->get(route('track.search', ['code' => $order->order_code]))
        ->assertOk();
});

test('tracking returns null for invalid code', function () {
    $this->get(route('track.search', ['code' => 'INVALID']))
        ->assertOk();
});

test('authenticated user can view edit order form', function () {
    $user = User::factory()->create();
    $order = Order::factory()->create(['created_by' => $user->id]);

    $this->actingAs($user)
        ->get(route('orders.edit', $order))
        ->assertOk();
});

test('authenticated user can edit an order', function () {
    $user = User::factory()->create();
    $order = Order::factory()->create(['created_by' => $user->id]);

    $this->actingAs($user)
        ->put(route('orders.update', $order), [
            'customer_name' => 'Updated Name',
            'customer_phone' => '09999999999',
            'customer_address' => 'New Address',
            'item_description' => 'Updated Item',
            'quantity' => 5,
            'total_price' => 75000,
            'notes' => 'Updated notes',
        ])
        ->assertRedirect();

    $order->refresh();
    expect($order->customer_name)->toBe('Updated Name');
    expect($order->item_description)->toBe('Updated Item');
    expect($order->quantity)->toBe(5);
});

test('edit order validates required fields', function () {
    $user = User::factory()->create();
    $order = Order::factory()->create(['created_by' => $user->id]);

    $this->actingAs($user)
        ->put(route('orders.update', $order), [])
        ->assertSessionHasErrors([
            'customer_name',
            'customer_phone',
            'customer_address',
            'item_description',
            'quantity',
            'total_price',
        ]);
});

test('authenticated user can delete an order', function () {
    $user = User::factory()->create();
    $order = Order::factory()->create(['created_by' => $user->id]);

    $this->actingAs($user)
        ->delete(route('orders.destroy', $order))
        ->assertRedirect(route('orders.index'));

    expect($order->fresh())->toBeNull();
});

test('guest cannot delete an order', function () {
    $order = Order::factory()->create();

    $this->delete(route('orders.destroy', $order))
        ->assertRedirect(route('login'));

    expect($order->fresh())->not->toBeNull();
});

test('authenticated user can search orders by customer name', function () {
    $user = User::factory()->create();
    Order::factory()->create(['created_by' => $user->id, 'customer_name' => 'Alice Smith']);
    Order::factory()->create(['created_by' => $user->id, 'customer_name' => 'Bob Jones']);

    $this->actingAs($user)
        ->get(route('orders.index', ['search' => 'Alice']))
        ->assertOk();
});

test('dashboard shows status counts', function () {
    $user = User::factory()->create();
    Order::factory()->count(2)->create(['created_by' => $user->id, 'status' => OrderStatus::Pending]);
    Order::factory()->approved()->create(['created_by' => $user->id]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk();
});

test('dashboard shows recent orders', function () {
    $user = User::factory()->create();
    Order::factory()->count(3)->create(['created_by' => $user->id]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk();
});

// Payment tracking tests

test('order can be created with amount_paid', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('orders.store'), [
            'customer_name' => 'Jane Doe',
            'customer_phone' => '09111111111',
            'customer_address' => '456 Oak St',
            'item_description' => 'DJ Stick',
            'quantity' => 1,
            'total_price' => 400000,
            'amount_paid' => 200000,
        ])
        ->assertRedirect();

    $order = Order::latest()->first();
    expect($order->amount_paid)->toBe('200000.00');
    expect($order->remaining_balance)->toBe('200000.00');
    expect($order->payment_status)->toBe('partial');
});

test('order defaults to zero amount_paid', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('orders.store'), [
            'customer_name' => 'Test',
            'customer_phone' => '09000000000',
            'customer_address' => 'Address',
            'item_description' => 'Item',
            'quantity' => 1,
            'total_price' => 100000,
        ])
        ->assertRedirect();

    $order = Order::latest()->first();
    expect($order->amount_paid)->toBe('0.00');
    expect($order->payment_status)->toBe('unpaid');
});

test('amount_paid cannot exceed total_price on store', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('orders.store'), [
            'customer_name' => 'Test',
            'customer_phone' => '09000000000',
            'customer_address' => 'Address',
            'item_description' => 'Item',
            'quantity' => 1,
            'total_price' => 100000,
            'amount_paid' => 200000,
        ])
        ->assertSessionHasErrors('amount_paid');
});

test('amount_paid can be updated via PATCH', function () {
    $user = User::factory()->create();
    $order = Order::factory()->create(['created_by' => $user->id, 'total_price' => 400000, 'amount_paid' => 0]);

    $this->actingAs($user)
        ->patch(route('orders.update', $order), [
            'amount_paid' => 200000,
        ])
        ->assertRedirect();

    expect($order->refresh()->amount_paid)->toBe('200000.00');
    expect($order->payment_status)->toBe('partial');
});

test('fully paid order has correct payment status', function () {
    $order = Order::factory()->fullyPaid()->create();

    expect($order->payment_status)->toBe('paid');
    expect($order->remaining_balance)->toBe('0.00');
});
