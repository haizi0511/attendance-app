<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Carbon\Carbon; 

class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $clockIn = Carbon::today()->setTime(rand(8, 10), rand(0, 59));
        $clockOut = (clone $clockIn)->addHours(rand(7, 9))->addMinutes(rand(0, 59));

        return [
            'user_id' => User::factory(),
            'clock_in' => $clockIn,
            'clock_out' => $clockOut,
            'status' => 3, // 退勤済み
        ];
    }
}
