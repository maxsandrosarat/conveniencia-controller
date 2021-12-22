<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->decimal('codigo_barras', 13, 0)->nullable();
            $table->string('nome');
            $table->string('embalagem');
            $table->string('marca');
            $table->decimal('preco_atual',6,2)->default(0);
            $table->integer('estoque')->default(0);
            $table->unsignedBigInteger('categoria_id');
            $table->boolean('ativo')->default(true);
            $table->foreign('categoria_id')->references('id')->on('categorias');
            $table->string('foto')->nullable();
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
        Schema::dropIfExists('produtos');
    }
}
