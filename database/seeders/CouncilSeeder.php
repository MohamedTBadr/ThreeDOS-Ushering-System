<?php

namespace Database\Seeders;

use App\Models\Council;
use Illuminate\Database\Seeder;

class CouncilSeeder extends Seeder
{
    public function run(): void
    {
        $councils = [
            [
                'name' => 'Backend Development',
                'description' => 'PHP, Laravel, MySQL, APIs, Authentication & System Architecture',
            ],
            [
                'name' => 'Frontend Development',
                'description' => 'HTML, CSS, JS, Responsive Design & UI/UX Principles',
            ],
            [
                'name' => 'CEO',
                'description' => 'Leadership, Strategy, Decision Making & Organization Management',
            ],
            [
                'name' => 'Marketing',
                'description' => 'Campaigns, Market Research, Branding, Outreach & Strategy',
            ],
        ];

        foreach ($councils as $c) {
            Council::updateOrCreate(['name' => $c['name']], $c);
        }
    }
}
