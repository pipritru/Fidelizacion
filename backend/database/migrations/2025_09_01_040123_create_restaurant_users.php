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
        Schema::create('restaurant_users', function (Blueprint $table) {
            $table->increments('id_restaurant_user'); // PK
            $table->unsignedInteger('id_restaurant');  // FK a restaurantes
            $table->unsignedInteger('id_user');       // FK a users
            $table->unsignedInteger('id_role');       // FK a roles (restaurantes tienen administrador)
            $table->timestamps();
            
            //FK

            // fk restaurant
            $table ->foreign('id_restaurant')
            ->references('id_restaurant')
            ->on ('restaurants')
            ->onDelete('cascade'); 

            // fk user

            $table ->foreign('id_user')
            ->references('id_user')
            ->on ('users')
            ->onDelete('cascade');// si se borra el usuario, se borran los restaurant_users asociados

            // fk role
            $table ->foreign('id_role')
            ->references('id_role')
            ->on ('roles')
            ->onDelete('cascade');// si se borra el rol, se borran los restaurant_users asociados
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_users');
    }
};
