<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendas', function (Blueprint $table) {
            $table->id();
            $table->enum('status',['nova','feita','paga','cancelada']);
            $table->decimal('valor_total',6,2)->default(0);
            $table->decimal('desconto_total',6,2)->default(0);
            $table->decimal('juros',6,2)->default(0);
            $table->decimal('total_final',6,2)->default(0);
            $table->integer('total_produtos')->default(0);
            $table->unsignedBigInteger('pagamento_forma_id')->nullable();
            $table->foreign('pagamento_forma_id')->references('id')->on('pagamento_formas');
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->string('observacao')->nullable();
            $table->string('usuario_criou')->nullable();
            $table->string('usuario_pagou')->nullable();
            $table->string('usuario_cancelou')->nullable();
            $table->string('motivo_cancelamento')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendas');
    }
}
