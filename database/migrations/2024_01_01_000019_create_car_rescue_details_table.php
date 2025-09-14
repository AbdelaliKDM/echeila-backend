<?php

use App\Constants\MalfunctionType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('car_rescue_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->onDelete('cascade');
            $table->foreignId('breakdown_point')->constrained('locations')->onDelete('cascade');
            $table->timestamp('delivery_time');
            $table->enum('malfunction_type', MalfunctionType::all());
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('car_rescue_details');
    }
};