<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompraProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compra_produtos', function (Blueprint $table) {
            $table->unsignedBigInteger('lista_compra_id');
            $table->unsignedBigInteger('produto_id');
            $table->integer('estoque');
            $table->foreign('lista_compra_id')->references('id')->on('lista_compras');
            $table->foreign('produto_id')->references('id')->on('produtos');
            $table->primary(['lista_compra_id','produto_id']);
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
        Schema::dropIfExists('compra_produtos');
    }
}
