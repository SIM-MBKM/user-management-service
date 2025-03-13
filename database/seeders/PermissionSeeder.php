<?php

namespace Database\Seeders;

use App\Models\GroupPermission;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $migrationService = GroupPermission::where('name', 'migration_service')->first();
        $authService          = GroupPermission::where('name', 'auth_service')->first();
        $activityManagement   = GroupPermission::where('name', 'activity_management_service')->first();
        $userManagement       = GroupPermission::where('name', 'user_management_service')->first();
        $matchingService      = GroupPermission::where('name', 'matching_service')->first();
        $statisticService     = GroupPermission::where('name', 'statistic_service')->first();
        $exportImportService  = GroupPermission::where('name', 'export_import_service')->first();
        $registrationService  = GroupPermission::where('name', 'registration_service')->first();
        $monitorEvalService   = GroupPermission::where('name', 'monitoring_evaluation_service')->first();
        $consultApproval      = GroupPermission::where('name', 'consultation_approval_service')->first();
        $calendarService      = GroupPermission::where('name', 'calendar_service')->first();

        $permissions = [
            // Migration Service
            // [
            //     'id' => Str::uuid(),
            //     'group_permission_id' => $authService->id,
            //     'name' => 'migration_service.create.?', -- perlu migration service kh
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],

            // Auth Service
            [
                'id' => Str::uuid(),
                'group_permission_id' => $authService->id,
                'name' => 'auth_service.create.users',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $authService->id,
                'name' => 'auth_service.read.users',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $authService->id,
                'name' => 'auth_service.update.users',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $authService->id,
                'name' => 'auth_service.delete.users',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // User Management
            [
                'id' => Str::uuid(),
                'group_permission_id' => $userManagement->id,
                'name' => 'user_management.create.roles',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $userManagement->id,
                'name' => 'user_management.read.roles',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $userManagement->id,
                'name' => 'user_management.update.roles',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $userManagement->id,
                'name' => 'user_management.delete.roles',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $userManagement->id,
                'name' => 'user_management.create.users',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $userManagement->id,
                'name' => 'user_management.read.users',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $userManagement->id,
                'name' => 'user_management.update.users',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $userManagement->id,
                'name' => 'user_management.delete.users',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $userManagement->id,
                'name' => 'user_management.create.permissions',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $userManagement->id,
                'name' => 'user_management.read.permissions',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $userManagement->id,
                'name' => 'user_management.update.permissions',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $userManagement->id,
                'name' => 'user_management.delete.permissions',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $userManagement->id,
                'name' => 'user_management.create.group_permissions',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $userManagement->id,
                'name' => 'user_management.read.group_permissions',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $userManagement->id,
                'name' => 'user_management.update.group_permissions',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $userManagement->id,
                'name' => 'user_management.delete.group_permissions',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $userManagement->id,
                'name' => 'user_management.create.role_permissions',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $userManagement->id,
                'name' => 'user_management.read.role_permissions',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $userManagement->id,
                'name' => 'user_management.update.role_permissions',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $userManagement->id,
                'name' => 'user_management.delete.role_permissions',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $userManagement->id,
                'name' => 'user_management.create.user_permissions',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $userManagement->id,
                'name' => 'user_management.read.user_permissions',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $userManagement->id,
                'name' => 'user_management.update.user_permissions',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $userManagement->id,
                'name' => 'user_management.delete.user_permissions',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            //===== SOON ===== 
            // Activity Management
            [
                'id' => Str::uuid(),
                'group_permission_id' => $activityManagement->id,
                'name' => 'activity_management.create.activities',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $activityManagement->id,
                'name' => 'activity_management.read.activities',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $activityManagement->id,
                'name' => 'activity_management.update.activities',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $activityManagement->id,
                'name' => 'activity_management.delete.activities',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Matching Service
            [
                'id' => Str::uuid(),
                'group_permission_id' => $matchingService->id,
                'name' => 'matching_service.create.matches',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $matchingService->id,
                'name' => 'matching_service.read.matches',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $matchingService->id,
                'name' => 'matching_service.update.matches',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $matchingService->id,
                'name' => 'matching_service.delete.matches',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Statistic Service
            [
                'id' => Str::uuid(),
                'group_permission_id' => $statisticService->id,
                'name' => 'statistic_service.create.statistics',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $statisticService->id,
                'name' => 'statistic_service.read.statistics',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $statisticService->id,
                'name' => 'statistic_service.update.statistics',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $statisticService->id,
                'name' => 'statistic_service.delete.statistics',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Export Import Service
            [
                'id' => Str::uuid(),
                'group_permission_id' => $exportImportService->id,
                'name' => 'export_import_service.create.exports',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $exportImportService->id,
                'name' => 'export_import_service.read.exports',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $exportImportService->id,
                'name' => 'export_import_service.update.exports',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $exportImportService->id,
                'name' => 'export_import_service.delete.exports',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $exportImportService->id,
                'name' => 'export_import_service.create.imports',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $exportImportService->id,
                'name' => 'export_import_service.read.imports',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $exportImportService->id,
                'name' => 'export_import_service.update.imports',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $exportImportService->id,
                'name' => 'export_import_service.delete.imports',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Registration Service
            [
                'id' => Str::uuid(),
                'group_permission_id' => $registrationService->id,
                'name' => 'registration_service.create.registrations',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $registrationService->id,
                'name' => 'registration_service.read.registrations',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $registrationService->id,
                'name' => 'registration_service.update.registrations',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $registrationService->id,
                'name' => 'registration_service.delete.registrations',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Monitoring & Evaluation
            [
                'id' => Str::uuid(),
                'group_permission_id' => $monitorEvalService->id,
                'name' => 'monitoring_evaluation_service.create.monitorings',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $monitorEvalService->id,
                'name' => 'monitoring_evaluation_service.read.monitorings',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $monitorEvalService->id,
                'name' => 'monitoring_evaluation_service.update.monitorings',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $monitorEvalService->id,
                'name' => 'monitoring_evaluation_service.delete.monitorings',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Consultation & Approval
            [
                'id' => Str::uuid(),
                'group_permission_id' => $consultApproval->id,
                'name' => 'consultation_approval_service.create.consultations',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $consultApproval->id,
                'name' => 'consultation_approval_service.read.consultations',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $consultApproval->id,
                'name' => 'consultation_approval_service.update.consultations',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $consultApproval->id,
                'name' => 'consultation_approval_service.delete.consultations',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $consultApproval->id,
                'name' => 'consultation_approval_service.create.approvals',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $consultApproval->id,
                'name' => 'consultation_approval_service.read.approvals',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $consultApproval->id,
                'name' => 'consultation_approval_service.update.approvals',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $consultApproval->id,
                'name' => 'consultation_approval_service.delete.approvals',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Calendar Service
            [
                'id' => Str::uuid(),
                'group_permission_id' => $calendarService->id,
                'name' => 'calendar_service.create.calendars',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $calendarService->id,
                'name' => 'calendar_service.read.calendars',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $calendarService->id,
                'name' => 'calendar_service.update.calendars',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'group_permission_id' => $calendarService->id,
                'name' => 'calendar_service.delete.calendars',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Bulk insert
        Permission::insert($permissions);
    }
}
