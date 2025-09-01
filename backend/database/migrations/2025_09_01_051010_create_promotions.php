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
        Schema::create('promotions', function (Blueprint $table) {
            $table->increments('id_promotion');         // PK
            $table->unsignedInteger('id_restaurant');   // FK a restaurants
            $table->string('name', 150);                 // Nombre de la promoción 
            $table->enum('type', ['percent', 'fixed', 'gift', 'bogo'])->default('percent'); // Tipo de promoción
            $table->decimal('value', 12, 2);             // Valor de la promoción 
            $table->unsignedInteger('min_tier_id')->nullable();  // FK a tiers (si la promoción está limitada a ciertos niveles)
            $table->decimal('min_purchase', 12, 2)->nullable();  // Monto mínimo de compra para activar la promoción
            $table->string('code', 40)->nullable();      // Código de promoción 
            $table->timestamp('starts_at');               // Fecha de inicio de la promoción
            $table->timestamp('ends_at')->nullable();    // Fecha de finalización de la promoción
            $table->integer('per_user_limit')->nullable(); // Límite de uso por cliente
            $table->integer('global_limit')->nullable();  // Límite global de uso para la promoción
            $table->boolean('stackable')->default(false); // Si la promoción se puede apilar con otras
            $table->boolean('active')->default(true);     // Si la promoción está activa o no
            $table->timestamps();

            // llaves foráneas

            //fk a restaurants
            $table->foreign('id_restaurant')
                  ->references('id_restaurant')
                  ->on('restaurants')
                  ->onDelete('cascade');  // Si borran un restaurante, eliminamos las promociones asociadas

           //fk a tiers
            $table->foreign('min_tier_id')
                  ->references('id_tier')
                  ->on('tiers')
                  ->onDelete('set null'); // Si borran un nivel, dejamos la FK en NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
