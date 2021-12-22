@extends('layouts.app', ["current"=>"relatorios"])

@section('body')
@php
	$page = "Relatório Vendas Clientes";
@endphp
    <div class="card border">
        <div class="card-body">
            <a href="/relatorios/vendas" class="btn btn-success"data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
            <br/><br/>
            <h5 class="card-title">Relatório de Vendas por Clientes</h5>
            @if(count($rels)==0)
                    <div class="alert alert-dark" role="alert">
                        @if($view=="inicial")
                        Sem movimentos cadastrados!
                        @else @if($view=="filtro")
                        Sem resultados da busca!
                        <a href="/relatorios/vendas/clientes" class="btn btn-success">Voltar</a>
                        @endif
                        @endif
                    </div>
            @else
            <div class="card border">
            <h5>Filtros: </h5>
            <form class="row gy-2 gx-3 align-items-center" method="GET" action="/relatorios/vendas/clientes/filtro">
                @csrf
                <div class="col-auto form-floating">
                    <select class="form-select" id="status" name="status">
                        <option value="">Selecione</option>
                        <option value="feita" style="color:blue; font-weight: bold;">Feita</option>
                        <option value="paga"style="color:green; font-weight: bold;">Paga</option>
                        <option value="cancelada" style="color:red; font-weight: bold;">Cancelada</option>
                    </select>
                    <label for="status">Status</label>
                </div>
                <div class="col-auto form-floating">
                    <select class="form-select" id="cliente" name="cliente">
                        <option value="">Selecione um cliente</option>
                        @foreach ($clientes as $cliente)
                        <option value="{{$cliente->id}}">{{$cliente->nome}}</option>
                        @endforeach
                    </select>
                    <label for="cliente">Cliente</label>
                </div>
                <div class="col-auto form-floating">
                    <input class="form-control mr-sm-2" type="date" name="dataInicio">
                    <label for="dataInicio">Data Início</label>
                </div>
                <div class="col-auto form-floating">
                    <input class="form-control mr-sm-2" type="date" name="dataFim">
                    <label for="dataFim">Data Fim</label>
                </div>
                <div class="col-auto">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Filtrar</button>
                </div>
            </form>
            </div>
            <br/>
            <div class="table-responsive-xl">
            <table id="yesprint" class="table table-striped table-ordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Pedido</th>
                        <th>Nome Cliente</th>
                        <th>Status</th>
                        <th>Total Pedido</th>
                        <th>Data & Hora</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rels as $rel)
                    <tr>
                        <td>{{$rel->id}}</td>
                        <td>{{$rel->cliente->nome}}</td>
                        <td @if($rel->status=='feita') style="color:blue; font-weight: bold;" @else @if($rel->status=='paga') style="color:green; font-weight: bold;" @else style="color:red; font-weight: bold;" @endif @endif>@if($rel->status=='feita') Feita @else @if($rel->status=='paga') Paga @else Cancelada @endif @endif</td>
                        <td>{{ 'R$ '.number_format($rel->total, 2, ',', '.')}}</td>
                        <td>{{date("d/m/Y H:i", strtotime($rel->created_at))}}</td>
                    </tr>
                    @endforeach
                    @if($view=="filtro")
                    <tr>
                        <td colspan="3">TOTAL GERAL:</td>
                        <td colspan="2">{{ 'R$ '.number_format($total_valor, 2, ',', '.')}}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
            </div>
            @endif
        </div>
        <div class="card-footer">
            {{ $rels->links() }}
        </div>
    </div>
@endsection