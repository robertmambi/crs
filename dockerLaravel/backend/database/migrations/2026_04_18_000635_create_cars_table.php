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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();

            // Owner (who owns the car)
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();

            // Basic Info
            $table->string('brand');
            $table->string('model');
            $table->year('year')->nullable();
            $table->string('plate_number')->unique();
            $table->string('vin_number')->nullable();

            // Specifications
            $table->string('color')->nullable();
            $table->enum('transmission', ['auto', 'manual'])->nullable();
            $table->enum('fuel_type', ['gas', 'diesel', 'electric', 'hybrid'])->nullable();
            $table->integer('seats')->nullable();

            // Pricing
            $table->decimal('price_per_day', 8, 2);

            // Status (business condition)
            $table->enum('status', ['active', 'maintenance', 'inactive'])
                  ->default('active');

            // Maintenance / Tracking
            $table->integer('mileage')->nullable();
            $table->date('last_oil_change_date')->nullable();

            // Legal Expiry Tracking
            $table->date('insurance_expiry_date')->nullable();
            $table->date('inspection_expiry_date')->nullable(); // keuringskaart
            $table->date('road_tax_expiry_date')->nullable();   // belasting

            // Media
            $table->string('image_url')->nullable();

            // Notes
            $table->text('description')->nullable();

            // System timestamps
            $table->timestamps();
            $table->softDeletes(); // optional but recommended
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
