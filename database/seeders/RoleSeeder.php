<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => UserRole::ADMIN->value,
                'display_name' => 'Administrator',
            ],
            [
                'name' => UserRole::RECRUITER->value,
                'display_name' => 'Recruiter',
            ],
            [
                'name' => UserRole::CANDIDATE->value,
                'display_name' => 'Candidate',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}
