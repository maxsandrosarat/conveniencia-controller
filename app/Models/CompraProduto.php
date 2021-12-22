<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompraProduto extends Model
{
    use HasFactory;

    public function produto(){
        return $this->belongsTo('App\Models\Produto');
    }

    public function lista_compra(){
        return $this->belongsTo('App\Models\ListaCompra');
    }
}
