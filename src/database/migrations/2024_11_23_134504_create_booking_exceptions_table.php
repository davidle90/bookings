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
            $table->string('label');
            $table->foreignId('bookable_id')->nullable()->constrained();
            $table->enum('type', ['block', 'available'])->default('block');

            $table->enum('recurring_type', ['none', 'daily', 'weekly'])->default('none');
            
            $table->dateTime('start_datetime')->nullable();
            $table->dateTime('end_datetime')->nullable();

            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            $table->text('notes')->nullable();
            $table->boolean('is_global')->default('false');
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
