<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDespesasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('despesas', function (Blueprint $table) {
            $table->id();
            $table->date("vencimento");
            $table->string("descricao");
            $table->float("valorTotal");
            $table->string("formaPagamento");
            $table->string('observacao')->nullable();
            $table->boolean('parcelado')->default(false);
            $table->float("valorParcela")->nullable();
            $table->integer('qtdParcelas')->nullable();
            $table->boolean('pago')->default(false);
            $table->date("pagamento")->nullable();
            $table->string('usuario');
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
        Schema::dropIfExists('despesas');
    }
}
