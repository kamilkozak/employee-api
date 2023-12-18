<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('residence_country');
            $table->string('residence_zip_code');
            $table->string('residence_city');
            $table->string('residence_street');
            $table->string('residence_building_number');
            $table->string('residence_apartment_number')->nullable();
            $table->boolean('different_correspondence_address');
            $table->string('correspondence_country');
            $table->string('correspondence_zip_code');
            $table->string('correspondence_city');
            $table->string('correspondence_street');
            $table->string('correspondence_building_number');
            $table->string('correspondence_apartment_number')->nullable();
            $table->timestamps();

            $table->unique('employee_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_addresses');
    }
};
