<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_name' => fake()->name(),
            'customer_phone' => fake()->phoneNumber(),
            'customer_address' => fake()->address(),
            'item_description' => fake()->words(3, true),
            'quantity' => fake()->numberBetween(1, 10),
            'total_price' => fake()->randomFloat(2, 1000, 100000),
            'amount_paid' => 0,
            'status' => OrderStatus::Pending,
            'notes' => fake()->optional()->sentence(),
            'created_by' => User::factory(),
        ];
    }

    public function approved(): static
    {
        return $this->state(['status' => OrderStatus::Approved]);
    }

    public function onTheWay(): static
    {
        return $this->state(['status' => OrderStatus::OnTheWay]);
    }

    public function delivered(): static
    {
        return $this->state(['status' => OrderStatus::Delivered]);
    }

    public function returned(): static
    {
        return $this->state(['status' => OrderStatus::Returned]);
    }

    public function partiallyPaid(): static
    {
        return $this->state(fn (array $attributes) => [
            'amount_paid' => round($attributes['total_price'] / 2, 2),
        ]);
    }

    public function fullyPaid(): static
    {
        return $this->state(fn (array $attributes) => [
            'amount_paid' => $attributes['total_price'],
        ]);
    }
}
