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
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id_permission');     // PK
            $table->string('code', 80)->unique();    // ejemplos de algunoos permisos que estan enlazados al rol 'orders.read', 'menu.write', 'loyalty.manage'
            $table->string('description', 200)->nullable();
            $table->timestamps();
            // $table->softDeletes();

            //Fk- tabla puente 

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
