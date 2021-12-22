<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutoSaida extends Model
{
    use HasFactory;

    public function produto(){
        return $this->belongsTo('App\Models\Produto');
    }
}
