<?php

namespace Database\Factories;

use App\Models\TransportUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransportUnitFactory extends Factory{
    protected $model = TransportUnit::class;

    public function definition(){
        return [
            'name' => $this->faker->word(),
            'type' => $this->faker->randomElement(['trailer', 'truck']),
        ];
    }
}