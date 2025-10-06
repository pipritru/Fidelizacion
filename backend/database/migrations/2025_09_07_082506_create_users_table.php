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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table -> string ('username') -> unique ();
            $table -> string ('password');
            $table -> foreignid('person_id') -> references('id') -> on('persons') -> onDelete('cascade') -> nullable() ;
            $table -> foreignid('role_id') -> references('id') -> on('roles') -> onDelete('cascade');
            $table -> boolean ('is_active') -> default (true);
            $table -> timestamp('last_login') -> nullable();
            $table -> timestamp('created_date') -> useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
