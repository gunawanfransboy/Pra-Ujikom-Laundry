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
        Schema::create('trans_order_detail', function (Blueprint $col) {
            $col->id();
            $col->foreignId('id_order')->constrained('trans_order')->onDelete('cascade');
            $col->foreignId('id_service')->constrained('type_of_service')->onDelete('cascade');
            $col->integer('qty');
            $col->double('subtotal');
            $col->text('notes')->nullable();
            $col->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trans_order_detail');
    }
};
