<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {

            $table->increments('id_user');                       // PK
            $table->unsignedInteger('id_person');                // FK a persons
            $table->string('username', 80)->unique();            // usuario (puede ser email u otro)
            $table->string('password_hash');                     // contraseña encriptada
            $table->enum('status', ['active', 'suspended'])
                  ->default('active');
            $table->timestamps();
            // $table->softDeletes();
            
            
            // Fk
            $table->foreign('id_person')
                  ->references('id_person')
                  ->on('persons')
                  ->onDelete('cascade'); // si se borra la persona, se borran sus usuarios
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
