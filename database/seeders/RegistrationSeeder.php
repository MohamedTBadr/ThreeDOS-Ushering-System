<?php

namespace Database\Seeders;

use App\Models\Registration;
use Illuminate\Database\Seeder;

class RegistrationSeeder extends Seeder
{
    public function run(): void
    {
        // Clean slate for idempotent reseeding
        // Registration::truncate();

        // Fixed examples covering filters / stats
        $fixed = [
            ['name' => 'Ahmed Mohamed', 'email' => 'ahmed.mohamed@example.com', 'phone' => '01012345678', 'college' => 'Cairo University', 'level' => 'Level 3', 'council' => 'Backend Development', 'event_type' => 'Offline', 'ushered_by' => 'vp_threedos', 'rating' => 'Acceptance', 'notes' => 'Strong Laravel knowledge', 'interview_time' => now()->addDays(2)],
            ['name' => 'Sara Ali', 'email' => 'sara.ali@example.com', 'phone' => '01123456789', 'college' => 'Helwan University', 'level' => 'Level 2', 'council' => 'Frontend Development', 'event_type' => 'Online', 'ushered_by' => 'head_frontend', 'rating' => 'B', 'notes' => 'Good UI sense, needs JS depth'],
            ['name' => 'Omar Hassan', 'email' => 'omar.hassan@example.com', 'phone' => '01234567890', 'college' => 'Ain Shams University', 'level' => 'Level 4', 'council' => 'Marketing', 'event_type' => 'Offline', 'ushered_by' => 'instructor_marketing', 'rating' => 'Rejection'],
            ['name' => 'Nour El-Din', 'email' => 'nour.eldin@example.com', 'phone' => '01098765432', 'college' => 'HU', 'level' => 'Level 1', 'council' => 'CEO', 'event_type' => 'Offline', 'ushered_by' => 'head_ceo', 'rating' => 'Pending'],
            ['name' => 'Layla Youssef', 'email' => 'layla.youssef@example.com', 'phone' => '01512345678', 'college' => 'Mansoura University', 'level' => 'Level 2', 'council' => 'Backend Development', 'event_type' => 'Online', 'ushered_by' => null, 'rating' => 'Pending', 'interview_time' => now()->addDays(5)],
            ['name' => 'Kareem Tarek', 'email' => 'kareem.tarek@example.com', 'phone' => '01011223344', 'college' => 'Alexandria University', 'level' => 'Level 3', 'council' => 'Stock Market', 'event_type' => 'Offline', 'ushered_by' => 'instructor_ceo', 'rating' => 'Acceptance'],
            ['name' => 'Mariam Hossam', 'email' => 'mariam.hossam@example.com', 'phone' => '01155667788', 'college' => 'Cairo University', 'level' => 'Level 1', 'council' => 'Frontend Development', 'event_type' => 'Offline', 'ushered_by' => 'vp_threedos', 'rating' => 'B'],
            ['name' => 'Youssef Adel', 'email' => 'youssef.adel@example.com', 'phone' => '01299887766', 'college' => 'Helwan University', 'level' => 'Level 4', 'council' => 'Marketing', 'event_type' => 'Offline', 'ushered_by' => 'head_marketing', 'rating' => 'Pending', 'interview_time' => now()->addDays(1)],
        ];

        foreach ($fixed as $row) {
            Registration::updateOrCreate(['email' => $row['email']], $row);
        }

        // Random bulk for pagination / stats testing (uses factory, skip duplicates)
        Registration::factory()->count(25)->create();
    }
}
