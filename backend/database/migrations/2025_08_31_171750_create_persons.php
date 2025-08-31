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
        Schema::create('persons', function (Blueprint $table) {
           $table->increments('id_person');               // PK autoincrement
            $table->string('first_name', 80);              // nombres
            $table->string('last_name', 80)->nullable();   // apellidos
            $table->string('national_id', 40)->nullable(); // cédula/ID
            $table->string('email', 150)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('address', 200)->nullable();
            $table->unsignedInteger('id_city')->nullable(); // FK opcional a cities
            $table->date('birthdate')->nullable();
            $table->timestamps();

             // $table->softDeletes(); // <- borradop lógico

             // repetidos 
            $tabl -> unique (['email']);
            $table -> unique (['national_id']);

            // llaves foraneas
            $table->foreign('id_city')
            ->references('id_city')
            ->on('cities')
            ->onDelete('set null'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persons');
    }
};
