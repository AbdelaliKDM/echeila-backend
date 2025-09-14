<?php

use App\Constants\TransactionType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->string('entity_type');
            $table->foreignId('trip_id')->constrained()->onDelete('cascade');
            $table->enum('type', TransactionType::all());
            $table->decimal('amount', 10, 2);
            $table->timestamps();
            
            $table->index(['entity_id', 'entity_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};