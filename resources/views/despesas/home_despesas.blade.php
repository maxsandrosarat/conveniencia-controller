@extends('layouts.app', ["current"=>"despesas"])

@section('body')
@php
	$page = "Home Despesas";
@endphp
<div class="container">
    <div class="row justify-content-center">
        <div class="col-auto">
            <div class="card border-primary text-center centralizado">
                <b><div class="card-header">
                    Despesas do 
                    <br/>Dia
                </div></b>
                <div class="card-body">
                    <h3 class="card-title">R$ {{ number_format($despesas['despesaDia'], 2, ',', '.') }}</h3>
                </div>
                <div class="card-footer text-muted">
                    <a href="/despesas/lancamentos/dia" class="btn btn-sm btn-primary">Detalhes</a>
                </div>
            </div>
        </div>
        <div class="col-auto">
            <div class="card border-primary text-center centralizado">
                <div class="card-header">
                    Despesas do 
                    <br/>Mês
                </div>
                <div class="card-body">
                    <h3 class="card-title">R$ {{ number_format($despesas['despesaMes'], 2, ',', '.') }}</h3>
                </div>
                <div class="card-footer text-muted">
                    <a href="/despesas/lancamentos/mes" class="btn btn-sm btn-primary">Detalhes</a>
                </div>
            </div>
        </div>
        <div class="col-auto">
            <div class="card border-primary text-center centralizado">
                <div class="card-header">
                    Despesas em
                    <br/>Aberto
                </div>
                <div class="card-body">
                    <h3 class="card-title">R$ {{ number_format($despesas['despesaAberto'], 2, ',', '.') }}</h3>
                </div>
                <div class="card-footer text-muted">
                    <a href="/despesas/lancamentos" class="btn btn-sm btn-primary">Detalhes</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection