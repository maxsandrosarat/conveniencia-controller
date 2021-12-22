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
                    <h5>Por Produtos</h5>
                    <p class="card-text">
                        Veja o relatório por produtos!
                    </p>
                    <a href="/relatorios/vendas/produtos" class="btn btn-primary">Verificar</a>
                </div>
            </div>
        </div>
        <div class="col-auto">
            <div class="card border-primary text-center centralizado">
                <div class="card-body">
                    <h5>Por Clientes</h5>
                    <p class="card-text">
                        Veja o relatório por clientes!
                    </p>
                    <a href="/relatorios/vendas/clientes" class="btn btn-primary">Verificar</a>
                </div>
            </div>
        </div>
        <div class="col-auto">
            <div class="card border-primary text-center centralizado">
                <div class="card-body">
                    <h5>Por Clientes & Produtos</h5>
                    <p class="card-text">
                        Veja o relatório por cliProd!
                    </p>
                    <a href="/relatorios/vendas/clientesProdutos" class="btn btn-primary">Verificar</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection