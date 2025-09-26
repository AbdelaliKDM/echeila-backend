<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cargo_transport_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_point')->constrained('locations')->onDelete('cascade');
            $table->timestamp('delivery_time');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cargo_transport_details');
    }
};