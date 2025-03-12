<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TestController extends Controller
{
    public function testDatabase()
    {
        try {
            // Check database connection
            DB::connection('user_management')->getPdo();
            
            // Check if the user_permissions table exists
            $tableExists = Schema::connection('user_management')->hasTable('user_permissions');

            return response()->json([
                'status' => 'success',
                'message' => 'Database connection successful.',
                'user_permissions_table_exists' => $tableExists
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Database connection failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
