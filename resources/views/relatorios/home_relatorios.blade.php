@extends('layouts.app', ["current"=>"relatorios"])

@section('body')
@php
	$page = "Home Cadastros";
@endphp
<div class="container">
    <div class="row justify-content-center">
        <div class="col-auto">
            <div class="card border-primary text-center centralizado">
                <div class="card-body">
                    <h5>Lista de Compra</h5>
                    <p class="card-text">
                        Crie uma lista!
                    </p>
                    <a href="/relatorios/listaCompras" class="btn btn-primary">Verificar</a>
                </div>
            </div>
        </div>
        <div class="col-auto">
            <div class="card border-primary text-center centralizado">
                <div class="card-body">
                    <h5>Estoque</h5>
                    <p class="card-text">
                        Veja suas entradas e saídas!
                    </p>
                    <a href="/relatorios/estoque" class="btn btn-primary">Verificar</a>
                </div>
            </div>
        </div>
        <div class="col-auto">
            <div class="card border-primary text-center centralizado">
                <div class="card-body">
                    <h5>Vendas</h5>
                    <p class="card-text">
                        Veja as suas vendas!
                    </p>
                    <a href="/relatorios/vendas" class="btn btn-primary">Verificar</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection