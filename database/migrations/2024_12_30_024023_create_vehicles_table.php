<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('vehicles', function (Blueprint $table) {
        $table->id();
        $table->string('vehicle_number')->unique();
        $table->enum('type', ['passenger', 'cargo']);
        $table->string('brand');
        $table->string('model');
        $table->integer('capacity');
        $table->enum('status', ['available', 'in_use', 'maintenance'])->default('available');
        $table->enum('ownership', ['company', 'rental']);
        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
