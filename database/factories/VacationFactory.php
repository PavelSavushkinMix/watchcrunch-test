<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class VacationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $start = $this->faker->dateTimeBetween('-5 months', '-2 months');
        $end = $this->faker->dateTimeBetween($start);

        return [
            'start' => $start,
            'end' => $end,
            'price' => $this->faker->randomFloat(2, 0, 1000),
        ];
    }
}
