<?php

namespace Database\Seeders;

use App\Models\ElectionSetting;
use Illuminate\Database\Seeder;

class ElectionSettingSeeder extends Seeder
{
    /**
     * Seed the default election settings.
     */
    public function run(): void
    {
        ElectionSetting::updateOrCreate(
            ['id' => 1],
            [
                'title' => 'Students Union Election 2026',
                'voting_open' => false,
                'results_published' => false,
            ]
        );
    }
}
