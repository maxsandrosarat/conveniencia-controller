<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagamentoFormasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagamento_formas', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->boolean('juros')->default(false);
            $table->enum('tipo_juros',['fixo','porc'])->nullable();
            $table->decimal('valor_juros',6,2)->default(0);
            $table->boolean('ativo')->default(true);
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
        Schema::dropIfExists('pagamento_formas');
    }
}
