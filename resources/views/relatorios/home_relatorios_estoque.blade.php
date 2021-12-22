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
                    <h5>Entradas</h5>
                    <p class="card-text">
                        Veja suas entradas!
                    </p>
                    <a href="/relatorios/estoque/entradas" class="btn btn-primary">Verificar</a>
                </div>
            </div>
        </div>
        <div class="col-auto">
            <div class="card border-primary text-center centralizado">
                <div class="card-body">
                    <h5>Saídas</h5>
                    <p class="card-text">
                        Veja as suas saídas!
                    </p>
                    <a href="/relatorios/estoque/saidas" class="btn btn-primary">Verificar</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection