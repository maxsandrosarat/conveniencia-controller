<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutoSaidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produto_saidas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto_id')->constrained()->cascadeOnDelete();
            $table->integer('quantidade_saida')->default(0);
            $table->decimal('custo',6,2)->default(0);
            $table->decimal('preco',6,2)->default(0);
            $table->decimal('desconto',6,2)->default(0);
            $table->decimal('lucro',6,2)->default(0);
            $table->string('usuario')->nullable();
            $table->enum('motivo',['venda','defeito','autoconsumo','ajuste'])->default('venda');
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
        Schema::dropIfExists('produto_saidas');
    }
}
