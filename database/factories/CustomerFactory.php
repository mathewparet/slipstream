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
        static $index = Customer::latest('id')->first()?->id | 0;

        return [
            'name' => $this->faker->company(),
            'category_id' => $this->faker->randomElement(Category::get()->pluck('id')),
            'reference' => 'CUST-'.++$index,
            'start_date' => $this->faker->date(),
            'description' => $this->faker->optional()->sentence()
        ];
    }
}
