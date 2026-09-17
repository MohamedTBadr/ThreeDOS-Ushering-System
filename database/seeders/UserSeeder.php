<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password123');

        $users = [
            // VP — sees all councils
            [
                'username' => 'vp_threedos',
                'email' => 'vp@threedos.test',
                'password' => $password,
                'role' => 'VP',
                'council' => null,
            ],
            // Backend Development
            [
                'username' => 'head_backend',
                'email' => 'head.backend@threedos.test',
                'password' => $password,
                'role' => 'Head',
                'council' => 'Backend Development',
            ],
            [
                'username' => 'instructor_backend',
                'email' => 'instructor.backend@threedos.test',
                'password' => $password,
                'role' => 'Instructor',
                'council' => 'Backend Development',
            ],
            // Frontend Development
            [
                'username' => 'head_frontend',
                'email' => 'head.frontend@threedos.test',
                'password' => $password,
                'role' => 'Head',
                'council' => 'Frontend Development',
            ],
            [
                'username' => 'instructor_frontend',
                'email' => 'instructor.frontend@threedos.test',
                'password' => $password,
                'role' => 'Instructor',
                'council' => 'Frontend Development',
            ],
            // CEO
            [
                'username' => 'head_ceo',
                'email' => 'head.ceo@threedos.test',
                'password' => $password,
                'role' => 'Head',
                'council' => 'CEO',
            ],
            [
                'username' => 'instructor_ceo',
                'email' => 'instructor.ceo@threedos.test',
                'password' => $password,
                'role' => 'Instructor',
                'council' => 'CEO',
            ],
            // Marketing
            [
                'username' => 'head_marketing',
                'email' => 'head.marketing@threedos.test',
                'password' => $password,
                'role' => 'Head',
                'council' => 'Marketing',
            ],
            [
                'username' => 'instructor_marketing',
                'email' => 'instructor.marketing@threedos.test',
                'password' => $password,
                'role' => 'Instructor',
                'council' => 'Marketing',
            ],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(['email' => $u['email']], $u);
        }
    }
}
