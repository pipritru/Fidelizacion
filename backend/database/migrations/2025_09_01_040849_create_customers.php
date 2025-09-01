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
        Schema::create('customers', function (Blueprint $table) {
           $table->increments('id_customer');           // PK
            $table->unsignedInteger('id_restaurant');    // FK a restaurants
            $table->unsignedInteger('id_user');          // FK a users
            $table->timestamps();

            // Llaves foráneaas

            // fk restaurant
            $table ->foreign('id_restaurant')
            ->references('id_restaurant')
            ->on ('restaurants')
            ->onDelete('cascade');

            // fk user
            
            $table ->foreign('id_user')
            ->references('id_user')
            ->on ('users')
            ->onDelete('cascade');// si se borra el usuario, se borran los customers asociadosss
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
