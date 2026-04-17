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
        Schema::table('trans_order', function (Blueprint $table) {
            $table->unsignedBigInteger('id_customer')->nullable()->change();
            $table->string('guest_name')->nullable();
            $table->string('guest_phone', 20)->nullable();
            $table->text('guest_address')->nullable();
            $table->integer('discount_member')->default(0);
            $table->integer('discount_voucher')->default(0);
            $table->unsignedBigInteger('id_voucher')->nullable();
            
            $table->foreign('id_customer')->references('id')->on('customers')->restrictOnDelete();
            $table->foreign('id_voucher')->references('id')->on('vouchers')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trans_order', function (Blueprint $table) {
            $table->dropForeign(['id_voucher']);
            $table->dropColumn([
                'guest_name', 'guest_phone', 'guest_address',
                'discount_member', 'discount_voucher', 'id_voucher'
            ]);
            $table->dropForeign(['id_customer']);
            $table->unsignedBigInteger('id_customer')->nullable(false)->change();
            $table->foreign('id_customer')->references('id')->on('customers')->restrictOnDelete();
        });
    }
};
