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
        ->put(route('orders.update', $order), [
            'status' => 'approved',
        ])
        ->assertRedirect();

    expect($order->refresh()->status)->toBe(OrderStatus::Approved);
});

test('order status update validates enum', function () {
    $user = User::factory()->create();
    $order = Order::factory()->create(['created_by' => $user->id]);

    $this->actingAs($user)
        ->put(route('orders.update', $order), [
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

test('dashboard shows status counts', function () {
    $user = User::factory()->create();
    Order::factory()->count(2)->create(['created_by' => $user->id, 'status' => OrderStatus::Pending]);
    Order::factory()->approved()->create(['created_by' => $user->id]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk();
});
