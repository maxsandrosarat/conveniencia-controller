@extends('layouts.app', ["current"=>"cadastros"])

@section('body')
@php
	$page = "Home Cadastros";
@endphp
<div class="container">
    <div class="row justify-content-center">
        <div class="col-auto">
            <div class="card border-primary text-center centralizado">
                <div class="card-body">
                    <h5>Categorias</h5>
                    <p class="card-text">
                        Gerenciar as Categorias
                    </p>
                    <a href="/cadastros/categorias" class="btn btn-primary">Gerenciar</a>
                </div>
            </div>
        </div>
        <div class="col-auto">
            <div class="card border-primary text-center centralizado">
                <div class="card-body">
                    <h5>Produtos</h5>
                    <p class="card-text">
                        Gerenciar os Produtos
                    </p>
                    <a href="/cadastros/produtos" class="btn btn-primary">Gerenciar</a>
                </div>
            </div>
        </div>
        <div class="col-auto">
            <div class="card border-primary text-center centralizado">
                <div class="card-body">
                    <h5>Formas de Pagamento</h5>
                    <p class="card-text">
                        Gerenciar as Formas
                    </p>
                    <a href="/cadastros/pagamentoFormas" class="btn btn-primary">Gerenciar</a>
                </div>
            </div>
        </div>
        <div class="col-auto">
            <div class="card border-primary text-center centralizado">
                <div class="card-body">
                    <h5>Clientes</h5>
                    <p class="card-text">
                        Gerenciar os Clientes
                    </p>
                    <a href="/cadastros/clientes" class="btn btn-primary">Gerenciar</a>
                </div>
            </div>
        </div>
        <div class="col-auto">
            <div class="card border-primary text-center centralizado">
                <div class="card-body">
                    <h5>Usuários</h5>
                    <p class="card-text">
                        Gerenciar os Usuários
                    </p>
                    <a href="/cadastros/user" class="btn btn-primary">Gerenciar</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection