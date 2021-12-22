@extends('layouts.app', ["current"=>"relatorios"])

@section('body')
@php
	$page = "Relatório Clientes Produtos";
@endphp
<div class="card border">
    <div class="card-body">
        <a href="/relatorios/vendas" class="btn btn-success"data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
        <br/><br/>
        <h5 class="card-title">Relatório de Clientes & Produtos</h5>
        @if(count($rels)==0)
                <div class="alert alert-dark" role="alert">
                    @if($view=="inicial")
                    Sem movimentos cadastrados!
                    @else @if($view=="filtro")
                    Sem resultados da busca!
                    <a href="/relatorios/estoque" class="btn btn-success">Voltar</a>
                    @endif
                    @endif
                </div>
        @else
        <div class="card border">
        <h5>Filtros: </h5>
        <form class="row gy-2 gx-3 align-items-center" method="GET" action="/relatorios/vendas/clientesProdutos/filtro">
            @csrf
            <div class="col-auto form-floating">
                <select class="form-select" id="status" name="status">
                    <option value="">Selecione o status</option>
                    <option value="feita" style="color:blue; font-weight: bold;">Feita</option>
                    <option value="paga"style="color: green; font-weight: bold;">Paga</option>
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
                    <th>Cliente</th>
                    <th>Nº Venda</th>
                    <th>Status</th>
                    <th>Produto</th>
                    <th>Preço</th>
                    <th>Desconto</th>
                    <th>Data & Hora</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($rels as $venda)
            @foreach ($venda->venda_produtos_itens as $venda_produto)
            <tr>
                <td>{{ $venda->cliente->nome}}</td>
                <td>{{ $venda->id }}</td>
                <td @if($venda->status=='feita') style="color:blue; font-weight: bold;" @else @if($venda->status=='paga') style="color:green; font-weight: bold;" @else style="color:red; font-weight: bold;" @endif @endif>@if($venda->status=='feita') Feita @else @if($venda->status=='paga') Paga @else Cancelada @endif @endif</td>
                <td>{{ $venda_produto->produto->nome }} {{$venda_produto->produto->marca}} - {{$venda_produto->produto->embalagem}}</td>      
                <td>R$ {{ number_format($venda_produto->produto->preco_atual, 2, ',', '.') }}</td>
                <td>R$ {{ number_format($venda_produto->desconto_total, 2, ',', '.') }}</td>
                <td>{{ $venda->created_at->format('d/m/Y H:i') }}</td>  
            </tr>
            @endforeach
            @endforeach
            </tbody>
        </table>
        <div class="card-footer">
            {{ $rels->links() }}
        </div>
        </div>
        @endif
    </div>
</div>
@endsection