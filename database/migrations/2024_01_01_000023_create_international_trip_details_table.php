<?php

use App\Constants\ArrivalPlace;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('international_trip_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->onDelete('cascade');
            $table->foreignId('starting_place')->constrained('locations')->onDelete('cascade');
            $table->enum('arrival_place', ArrivalPlace::all());
            $table->timestamp('starting_time');
            $table->timestamp('arrival_time');
            $table->integer('total_seats');
            $table->decimal('seat_price', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('international_trip_details');
    }
};