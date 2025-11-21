<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class ItemFactory extends Factory
{

    public function definition(): array
    {
        return [
            // Membuat data item
            'name' => $this->faker->name(),
            'category_id' => $this->faker->numberBetween(1, 2),
            'price' => $this->faker->randomFloat(2, 1000, 100000),
            'description' => $this->faker->text(),
            // menampilkan gambar dari internet
            'img' => fake()->randomElement(
                [
                'https://plus.unsplash.com/premium_photo-1694547924505-caf71944b4df?q=80&w=822&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'https://plus.unsplash.com/premium_photo-1668143358351-b20146dbcc02?w=900&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8c3VzaGklMjBmb29kfGVufDB8fDB8fHww',
                'https://images.unsplash.com/photo-1652752731860-ef0cf518f13a?q=80&w=1740&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'
                ]
            ),
            'is_active' => $this->faker->boolean(),
        ];
    }
}
