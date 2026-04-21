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
        Schema::create('trans_laundry_pickup', function (Blueprint $col) {
            $col->id();
            $col->foreignId('id_order')->constrained('trans_order')->onDelete('cascade');
            $col->foreignId('id_customer')->constrained('customers')->onDelete('cascade');
            $col->dateTime('pickup_date');
            $col->text('notes')->nullable();
            $col->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trans_laundry_pickup');
    }
};
