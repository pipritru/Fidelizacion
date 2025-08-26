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
            $table->id();
            $table->foreignId('state_id')->constrained('states')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('name');                 
            $table->string('code', 10)->nullable(); //codigo de la iso
            $table->timestamps();

            $table->unique(['state_id','name']); // "Bucaramanga" puede existir en 1 estado, no repetir dentro del mismo
            // $table->unique(['code']); // descomentar si utilizo códigos únicos por ciudad
            $table->index(['name']); 
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
