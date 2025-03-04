<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $index = Customer::latest()->first()->id;

        return [
            'name' => $this->faker->company(),
            'category' => $this->faker->randomElement(Category::all()),
            'reference' => 'CUST-'.++$index,
            'start_date' => $this->faker->date(),
            'description' => $this->faker->optional()->sentence()
        ];
    }
}
