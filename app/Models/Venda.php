<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    use HasFactory;

    public function pagamento_forma(){
        return $this->belongsTo('App\Models\PagamentoForma');
    }

    public function venda_produtos()
    {
        return $this->hasMany('App\Models\VendaProduto');
    }

    public function cliente(){
        return $this->belongsTo('App\Models\Cliente');
    }

    function produtos(){
        return $this->belongsToMany("App\Models\Produto", "venda_produtos");
    }

    public function venda_produtos_itens()
    {
        return $this->hasMany('App\Models\VendaProduto');
    }

}
