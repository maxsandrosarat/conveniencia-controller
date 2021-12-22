<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutoExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produto_extras', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->unsignedBigInteger('lista_compra_id');
            $table->foreign('lista_compra_id')->references('id')->on('lista_compras');
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
        Schema::dropIfExists('produto_extras');
    }
}
