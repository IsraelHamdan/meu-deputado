<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    public function up()
    {
        Schema::create('deputados', function (Blueprint $table) {
            $table->id(); // ID interno
            $table->integer('camara_id')->unique();
            $table->string('nome');
            $table->string('email')->nullable();
            $table->integer('id_legislatura');
            $table->string('sigla_partido');
            $table->string('sigla_uf');
            $table->string('uri');
            $table->string('uri_partido');
            $table->string('url_foto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deputados');
    }
};
