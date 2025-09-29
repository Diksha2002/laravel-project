<?php   
// database/factories/ShopFactory.php

namespace Database\Factories;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShopFactory extends Factory
{
    protected $model = Shop::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'address' => $this->faker->address, // Ensure this field is present
            'email' => $this->faker->unique()->safeEmail,
            // other fields if needed...
        ];
    }
}
