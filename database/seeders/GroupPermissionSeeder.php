<?php

namespace Database\Seeders;

use App\Models\GroupPermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GroupPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            [
                'id' => Str::uuid(),
                'name' => 'migration_service',
                'description' => 'Group for migration service',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'auth_service',
                'description' => 'Group for auth service',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'activity_management_service',
                'description' => 'Group for activity management service',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'user_management_service',
                'description' => 'Group for user management service',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'matching_service',
                'description' => 'Group for matching service',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'statistic_service',
                'description' => 'Group for statistic service',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'export_import_service',
                'description' => 'Group for export/import service',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'registration_service',
                'description' => 'Group for registration service',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'monitoring_evaluation_service',
                'description' => 'Group for monitoring & evaluation service',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'consultation_approval_service',
                'description' => 'Group for consultation & approval service',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'calendar_service',
                'description' => 'Group for calendar service',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        GroupPermission::insert($groups);
    }
}
