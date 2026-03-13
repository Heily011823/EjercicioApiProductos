<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVerificationCodeToUsersTable extends Migration
{
    /**
     * Ejecuta la migración.
     * Agrega las columnas para el código de verificación
     * y la fecha de expiración del código.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->string('verification_code')->nullable(); // código de verificación
            $table->timestamp('code_expires_at')->nullable(); // fecha de expiración del código

        });
    }

    /**
     * Revierte la migración.
     * Elimina las columnas agregadas.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn('verification_code');
            $table->dropColumn('code_expires_at');

        });
    }
}