<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {

            $table->id();
			
		// 🔹 Basic Info
			$table->string('first_name', 100);
			$table->string('last_name', 100);
	
            //$table->string('name');
            //$table->string('email')->unique();
            //$table->timestamp('email_verified_at')->nullable();
            //$table->string('password');			
			
			$table->string('email', 150)->unique()->nullable();
			$table->string('password')->nullable();
			$table->string('phone', 20)->nullable();			

		// 🔹 Roles & Status
			$table->enum('role', ['admin', 'operator', 'customer', 'owner']);
			$table->enum('status', ['active', 'pending', 'suspended', 'blocked'])->default('pending');

		// 🔹 Verification
			$table->timestamp('email_verified_at')->nullable();
			$table->timestamp('phone_verified_at')->nullable();

		// 🔹 KYC / Driver Approval
			$table->enum('kyc_status', ['pending', 'approved', 'rejected'])->default('pending');
			$table->enum('driver_status', ['pending', 'approved', 'rejected'])->default('pending');

		// 🔹 Identity
			$table->enum('id_type', ['driver_license', 'passport'])->nullable();
			$table->string('id_number', 100)->nullable();
			$table->string('id_image')->nullable();

		// 🔹 UX / Tracking
			$table->boolean('profile_completed')->default(false);
			$table->timestamp('last_login_at')->nullable();

			
		// 🔐 Laravel Auth (KEEP THESE)			
            $table->rememberToken();
            $table->timestamps();
			$table->softDeletes();

        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
