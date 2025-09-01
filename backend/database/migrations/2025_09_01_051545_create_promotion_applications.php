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
        Schema::create('promotion_applications', function (Blueprint $table) {
           $table->increments('id_promo_app');          // PK
            $table->unsignedInteger('id_promotion');     // FK a promotions
            $table->unsignedInteger('id_customer');      // FK a customers
            $table->unsignedInteger('id_purchase');      // FK a purchases
            $table->decimal('applied_value', 12, 2);      // Valor aplicado de la promoción
            $table->timestamp('applied_at')->nullable();  // Fecha de aplicación
            $table->timestamps();

            // llaves foráneas

            // fk a promotions
            $table->foreign('id_promotion')
                  ->references('id_promotion')
                  ->on('promotions')
                  ->onDelete('cascade');  // Si borran una promoción, eliminamos su aplicación

            // fk a customers
            $table->foreign('id_customer')
                  ->references('id_customer')
                  ->on('customers')
                  ->onDelete('cascade');  // Si borran un cliente, eliminamos la aplicación de la promoción

            // fk a purchases
            $table->foreign('id_purchase')
                  ->references('id_purchase')
                  ->on('purchases')
                  ->onDelete('cascade');  // Si borran una compra, eliminamos la aplicación de la promoción
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_applications');
    }
};
