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
        Schema::create('restaurants', function (Blueprint $table) {
            $table->increments('id_restaurant');              // PK
            $table->string('legal_name', 150);                // Razón social
            $table->string('trade_name', 150)->nullable();    // Nombre comercial
            $table->string('slug', 120)->unique();            // identificador único URL
            $table->string('tax_id', 40)->nullable();         // NIT/RUT
            $table->string('phone', 30)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('address', 200)->nullable();
            $table->unsignedInteger('id_city')->nullable();   // FK hacia cities
            $table->string('timezone', 64)->default('America/Bogota');
            $table->timestamps();
            // $table->softDeletes();   


            //Fk
            $table -> foreign('id_city')
            ->references('id_city')
            ->on ('cities')
            ->oncascade('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
