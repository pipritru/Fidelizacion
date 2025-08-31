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
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->unsignedInteger('id_role');        // FK a roles
            $table->unsignedInteger('id_permission');  // FK a permissions
            $table->timestamps();

            $table->primary(['id_role', 'id_permission']); // PK compuesta

            // Llaves foráneas
            $table->foreign('id_role')
                ->references('id_role')
                ->on('roles')
                ->onDelete('cascade');

            $table->foreign('id_permission')
                ->references('id_permission')
                ->on('permissions')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
