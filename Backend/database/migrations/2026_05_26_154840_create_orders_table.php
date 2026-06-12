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
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->string('customer_name', 255);
            $table->string('customer_phone', 20);
            $table->string('customer_email', 255);
            $table->string('service_name', 255);
            $table->decimal('total_price', 12, 2);
            $table->enum('status', ['Dalam Antrian', 'Sedang Diproses', 'Selesai', 'Siap Diambil', 'Siap Dikirim','Dibatalkan'])->default('Dalam Antrian');
            $table->string('file_path')->nullable();
            $table->string('notes')->nullable();
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
