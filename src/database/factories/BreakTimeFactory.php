<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon; 

class BreakTimeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $start = Carbon::today()->setTime(rand(12, 13), rand(0, 59));

        $end = (clone $start)->addMinutes(rand(30, 60));

        return [
            'break_start' => $start,
            'break_end' => $end,
        ];
    }
}
