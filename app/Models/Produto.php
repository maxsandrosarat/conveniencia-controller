<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    public function categoria(){
        return $this->belongsTo('App\Models\Categoria');
    }

    public function precos(){
        return $this->hasMany('App\Models\ProdutoPreco');
    }

    public function entradas(){
        return $this->hasMany('App\Models\ProdutoEntrada');
    }

    public function saidas(){
        return $this->hasMany('App\Models\ProdutoSaida');
    }
}
