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
        Schema::create('cities', function (Blueprint $table) {
             $table->increments('id_city');                // PK autoincrement
            $table->string('name', 120);                  // nombre de la ciudad
            $table->unsignedInteger('id_state');          // FK hacia states
            $table->timestamps();

            // llaves foraneas
            $table->foreign('id_state')
            ->references('id_state')
            ->on('state')
            ->onDelete('cascade');

            $table -> unique(['name', 'id_state']); // una ciudad no se puede repetir en un mismo estado
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
