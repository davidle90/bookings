<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_exceptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bookable_id')->constrained()->onDelete('cascade');
            $table->date('exception_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('type')->default('block'); // 'block' (unavailable) or 'available' (override availability)
            $table->text('notes')->nullable(); 
            $table->timestamps();
        });
    }

    /**add ProductsController.php
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_exceptions');
    }
};
