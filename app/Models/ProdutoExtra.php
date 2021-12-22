<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutoExtra extends Model
{
    use HasFactory;

    public function lista(){
        return $this->belongsTo('App\Models\ListaCompra');
    }
}
