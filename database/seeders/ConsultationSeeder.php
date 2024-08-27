<?php

namespace Database\Seeders;

use App\Models\Consultation;
use Illuminate\Database\Seeder;

class ConsultationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arr = ['Medical consultation', 'Work consultation', 'Psychological counseling', 'Family counseling', 'Administrative consulting'];
        foreach ($arr as $consultation) {
            Consultation::create(['type' => $consultation]);
        }
    }
}
