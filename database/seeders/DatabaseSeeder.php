<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Abd Rahman',
            'email' => 'abd@gmail.com',
            'password' => bcrypt('abd11123'),
            'role' => 'admin',
        ]);
        $this->call([ConsultationSeeder::class]);
    }
}
