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
        Schema::create('trans_order', function (Blueprint $col) {
            $col->id();
            $col->foreignId('id_customer')->nullable()->constrained('customers')->onDelete('set null');
            $col->string('order_code');
            $col->date('order_date');
            $col->date('order_end_date');
            $col->integer('order_status')->default(0);
            $col->double('subtotal');
            $col->double('tax')->default(0);
            $col->double('order_pay')->default(0);
            $col->double('order_change')->default(0);
            $col->double('total');
            $col->string('guest_name')->nullable();
            $col->string('guest_phone')->nullable();
            $col->text('guest_address')->nullable();
            $col->double('discount_member')->default(0);
            $col->double('discount_voucher')->default(0);
            $col->foreignId('id_voucher')->nullable()->constrained('vouchers')->onDelete('set null');
            $col->timestamps();
            $col->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trans_order');
    }
};
