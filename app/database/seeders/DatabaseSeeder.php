<?php

namespace Database\Seeders;

// use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\State;


class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run(): void {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Possible states
        State::create([
            'id' => 1,
            'label' => 'Waiting',
        ]);
        State::create([
            'id' => 2,
            'label' => 'Running',
        ]);
        State::create([
            'id' => 3,
            'label' => 'Failed',
        ]);
        State::create([
            'id' => 4,
            'label' => 'Succeeded',
        ]);
    }
}
