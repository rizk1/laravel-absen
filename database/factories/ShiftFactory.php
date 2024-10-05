<?php

namespace Database\Factories;

use App\Models\Shift;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShiftFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Shift::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'shift' => $this->faker->randomElement(['Shift 1', 'Shift 2', 'Shift 3', 'Non Shift']),
            'mulai' => $this->faker->randomElement(['07:00', '15:00', '23:00', '09:00']),
            'selesai' => $this->faker->randomElement(['15:00', '23:00', '07:00', '06:00']),
        ];
    }
}
