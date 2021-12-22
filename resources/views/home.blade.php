@extends('layouts.app', ["current"=>"home"])

@section('body')
@php
	$page = "Home";
@endphp
<div class="container">
    <div class="row justify-content-center">
        <div class="col-auto">
            <div class="card border-primary text-center centralizado">
                <b><div class="card-header">
                    Lucro <br/><h4><b>Hoje</b></h4>
                </div></b>
                <div class="card-body">
                    <h2 class="card-title">{{ 'R$ '.number_format($lucro_hoje, 2, ',', '.')}}</h2>
                    
                </div>
                <div class="card-footer text-muted">
                    <a href="/saidas" class="btn btn-sm btn-primary">Detalhes</a>
                </div>
            </div>
        </div>
        <div class="col-auto">
            <div class="card border-primary text-center centralizado">
                <b><div class="card-header">
                    Lucro <br/><h4><b>Dia Anterior</b></h4>
                </div></b>
                <div class="card-body">
                    <h2 class="card-title">{{ 'R$ '.number_format($lucro_anterior, 2, ',', '.')}}</h2>
                    
                </div>
                <div class="card-footer text-muted">
                    <a href="/saidas" class="btn btn-sm btn-primary">Detalhes</a>
                </div>
            </div>
        </div>
        <div class="col-auto">
            <div class="card border-primary text-center centralizado">
                <b><div class="card-header">
                    Lucro <br/><h4><b>Mês Atual</b></h4>
                </div></b>
                <div class="card-body">
                    <h2 class="card-title">{{ 'R$ '.number_format($lucro_mes, 2, ',', '.')}}</h2>
                    
                </div>
                <div class="card-footer text-muted">
                    <a href="/saidas" class="btn btn-sm btn-primary">Detalhes</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection