<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        User::factory()->admin()->create();

        $users = User::factory(10)->create();

        foreach ($users as $user) {

            $attendance = Attendance::factory()->create([
                'user_id' => $user->id,
            ]);

            $breaks = BreakTime::factory(rand(1, 2))->create();

            foreach ($breaks as $break) {
                $attendance->breakTimes()->attach($break->id);
            }
        }
    }
}
