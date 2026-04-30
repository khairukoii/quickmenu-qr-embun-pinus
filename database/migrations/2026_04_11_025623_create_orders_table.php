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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('table_number'); // Meja pelanggan
            $table->string('customer_name')->nullable(); // Nama pelanggan, 
            $table->string('payment_method')->nullable(); // Metode pembayaran,
            $table->json('items'); // Isi pesanan
            $table->integer('total_price'); // Total bayar
            $table->string('status')->default('pending'); // Kolom status yang tadi error
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
