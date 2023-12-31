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
        Schema::create('service_orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("vehiclePlate", 7);
            $table->dateTime("entryDateTime");
            $table->dateTime("exitDateTime")->default("0001-01-01 00:00:00");
            $table->string("priceType", 55)->nullable();
            $table->decimal("price", $precision = 12, $scale = 2);
            $table->unsignedBigInteger("userId");
            $table->foreign("userId")->references("id")->on("users");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_orders');
    }
};
