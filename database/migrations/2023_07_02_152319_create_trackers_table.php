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
        Schema::create('trackers', function (Blueprint $table) {
            $table->id();
            $table->string('tracker_id')->nullable();
            $table->dateTime('timestamp')->nullable();
            $table->string('model')->nullable();
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
            $table->float('temperature')->nullable();
            $table->float('battery')->nullable();
            $table->integer('gps_valid_position')->nullable();
            $table->integer('network_rsrp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trackers');
    }
};
