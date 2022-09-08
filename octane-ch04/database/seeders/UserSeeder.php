<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        $passwordEnc = Hash::make(fake()->password());
        for ($i = 0; $i < 1000; $i++) {
            $data[] =
            [
                'name' => fake()->firstName(),
                'email' => fake()->email(),
                'password' => $passwordEnc,
            ];
        }
        foreach (array_chunk($data, 100) as $chunk) {
            User::insert($chunk);
        }
    }
}
