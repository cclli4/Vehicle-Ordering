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
    Schema::create('bookings', function (Blueprint $table) {
        $table->id();
        $table->string('booking_number')->unique();
        $table->foreignId('user_id')->constrained();
        $table->foreignId('vehicle_id')->constrained();
        $table->foreignId('driver_id')->constrained('users');
        $table->text('purpose');
        $table->dateTime('start_date');
        $table->dateTime('end_date');
        $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
        $table->integer('current_approval_level')->default(1);
        $table->text('notes')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
