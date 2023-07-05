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
        Schema::create('sensors', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('id_frame')->nullable();
            $table->string('value')->nullable();
            $table->string('rssi')->nullable();
            $table->unsignedBigInteger('tracker_id');
            $table->foreign('tracker_id')->references('id')->on('trackers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensors');
    }
};
