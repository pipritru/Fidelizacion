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
        Schema::create('transaction_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loyalty_point_id')->constrained('loyalty_points')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null'); // Opcional si no siempre relacionado con orden
            $table->integer('points');
            $table->enum('type', ['earn', 'redeem', 'adjustment'])->default('earn');
            $table->timestamp('transaction_date')->useCurrent();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_points');
    }
};
