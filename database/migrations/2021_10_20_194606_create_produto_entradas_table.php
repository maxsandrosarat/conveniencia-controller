<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutoEntradasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produto_entradas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto_id')->constrained()->cascadeOnDelete();
            $table->decimal('custo',6,2)->default(0);
            $table->integer('quantidade_entrada')->default(0);
            $table->integer('quantidade_saida')->default(0);
            $table->boolean('finalizado')->default(false);
            $table->string('usuario')->nullable();
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
        Schema::dropIfExists('produto_entradas');
    }
}
