<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        for ($i = 0; $i < 100_000; $i++) {
            $data[] = [
                'user_id' => random_int(1, 1000),
                'type' => Arr::random(
                    [
                        'ALERT', 'WARNING', 'INFO',
                    ]
                ),
                'description' => fake()->realText(),
                'value' => random_int(1, 10),
                'date' => fake()->dateTimeThisYear(),
            ];
        }
        foreach (array_chunk($data, 100) as $chunk) {
            Event::insert($chunk);
        }
    }
}
