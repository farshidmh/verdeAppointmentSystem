<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {

        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('agents');
            $table->foreignId('customer_id')->constrained('customers');
            $table->dateTime('datetime_begin');
            $table->dateTime('datetime_end');
            $table->dateTime('datetime_to_leave')->nullable();
            $table->string('address');
            $table->integer('distance')->nullable();

            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
};