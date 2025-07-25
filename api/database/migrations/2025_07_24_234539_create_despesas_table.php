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
        Schema::create('despesas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deputado_id')->constrained()->onDelete('cascade');

            $table->integer('ano');
            $table->string('cnpj_cpf_fornecedor');
            $table->bigInteger('cod_documento');
            $table->bigInteger('cod_lote');
            $table->integer('cod_tipo_documento');
            $table->date('data_documento')->nullable();
            $table->integer('mes');
            $table->string('nome_fornecedor');
            $table->string('num_documento');
            $table->string('num_ressarcimento');
            $table->integer('parcela');
            $table->string('tipo_despesa');
            $table->string('tipo_documento');
            $table->string('url_documento');
            $table->decimal('valor_documento', 12, 2);
            $table->decimal('valor_glosa', 12, 2);
            $table->decimal('valor_liquido', 12, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('despesas');
    }
};
