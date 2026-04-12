<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update existing data first
        DB::table('users')
            ->where('role', 'owner')
            ->update(['role' => 'carowner']);

        // 2. Change ENUM definition
        DB::statement("
            ALTER TABLE users 
            MODIFY role ENUM('admin', 'operator', 'customer', 'carowner') 
            NOT NULL
        ");
    }

    public function down(): void
    {
        // revert data
        DB::table('users')
            ->where('role', 'carowner')
            ->update(['role' => 'owner']);

        // revert ENUM
        DB::statement("
            ALTER TABLE users 
            MODIFY role ENUM('admin', 'operator', 'customer', 'owner') 
            NOT NULL
        ");
    }
};
