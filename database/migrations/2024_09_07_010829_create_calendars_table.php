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
        Schema::create('calendars', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('visitor_name');
            $table->string('visitor_mobile');
            $table->string('visitor_email');
            $table->string('vehicle_number');
            $table->string('vehicle_color');
            $table->string('vehicle_model');
            $table->enum('has_arrival_booking', ['Yes', 'No'])->default('Yes');
            $table->string('arrival_mode')->nullable();
            $table->dateTime('arrival_datetime')->nullable();
            $table->enum('has_hold_luggage', ['Yes', 'No'])->nullable();
            $table->enum('has_departure_booking', ['Yes', 'No'])->default('Yes');
            $table->dateTime('departure_meeting_time')->nullable();
            $table->text('additional_info')->nullable();
            $table->string('google_event_id')->nullable()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendars');
    }
};
