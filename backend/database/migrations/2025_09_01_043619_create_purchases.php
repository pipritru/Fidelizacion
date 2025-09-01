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
        Schema::create('purchases', function (Blueprint $table) {
            $table->increments('id_purchase');             // PK
            $table->unsignedInteger('id_restaurant');      // FK a restaurants
            $table->unsignedInteger('id_customer')->nullable(); // FK a customers (opcional en caso de que no esté registrado como cliente)
            $table->decimal('total_amount', 12, 2);        // Monto total de la compra
            $table->string('currency', 10)->default('COP'); // Moneda de la compra (por defecto COP)
            $table->enum('status', ['paid', 'void', 'refunded'])->default('paid');  // Estado de la compra
            $table->timestamp('purchased_at')->nullable(); // Fecha de compra
            $table->json('metadata')->nullable();          // Datos adicionales como folio, mesa, canal, etc.
            $table->timestamps();

            // Llaves foráneas
            
            // fk restaurant
            $table->foreign('id_restaurant')
                ->references('id_restaurant')
                ->on('restaurants')
                ->onDelete('cascade'); // si se borra el restaurante, se borran las compras asociadas

            // fk customer
            $table->foreign('id_customer')
                ->references('id_customer')
                ->on('customers')
                ->onDelete('set null'); // si se borra el cliente, se pone null en las compras asociadas
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
