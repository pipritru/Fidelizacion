<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Identificación básica
            $table->string('name');                           // Nombre completo
            $table->string('email')->nullable()->unique();    // Correo (opcional, único)
            $table->string('phone', 30)->nullable()->unique();// Teléfono (opcional, único)

            // Documento (útil para programas de puntos y facturación)
            $table->string('document_type', 20)->nullable();  // CC, CE, PASS, NIT, etc.
            $table->string('document_number', 50)->nullable()->unique();

            // Credenciales (si el mismo user accede a backoffice/app)
            $table->string('password')->nullable();

            // Perfil opcional
            $table->unsignedBigInteger('city_id')->nullable()->index(); // FK se agrega cuando exista cities
            $table->date('birthdate')->nullable();
            $table->enum('gender', ['male','female','other','unspecified'])->default('unspecified');

            // Preferencias y estado
            $table->boolean('marketing_opt_in')->default(false); // permiso de marketing
            $table->timestamp('last_login_at')->nullable();
            $table->enum('status', ['active','inactive','blocked'])->default('active');

            // Auditoría
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            // Índices compuestos opcionales (descomenta si aplican a tu negocio)
            // $table->index(['document_type', 'document_number']);
            // $table->index(['status', 'city_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
