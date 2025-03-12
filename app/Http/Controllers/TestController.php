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
            $tableExists = Schema::connection('pgsql')->hasTable('user_permissions');

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
