@extends('layouts.app', ["current"=>"relatorios"])

@section('body')
@php
	$page = "Relatório Vendas Produtos";
@endphp
    <div class="card border">
        <div class="card-body">
            <a href="/relatorios/vendas" class="btn btn-success"data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
            <br/><br/>
            <h5 class="card-title">Relatório de Vendas por Produtos</h5>
            @if(count($rels)==0)
                    <div class="alert alert-dark" role="alert">
                        @if($view=="inicial")
                        Sem movimentos cadastrados!
                        @else @if($view=="filtro")
                        Sem resultados da busca!
                        <a href="/relatorios/vendas/produtos" class="btn btn-success">Voltar</a>
                        @endif
                        @endif
                    </div>
            @else
            <div class="card border">
            <h5>Filtros: </h5>
            <form class="row gy-2 gx-3 align-items-center" method="GET" action="/relatorios/vendas/produtos/filtro">
                @csrf
                <div class="col-auto form-floating">
                    <select class="form-select" id="status" name="status">
                        <option value="">Selecione</option>
                        <option value="feito" style="color:blue; font-weight: bold;">Feito</option>
                        <option value="pago"style="color:green; font-weight: bold;">Pago</option>
                        <option value="cancelado" style="color:red; font-weight: bold;">Cancelado</option>
                    </select>
                    <label for="status">Status</label>
                </div>
                <div class="col-auto form-floating">
                    <select class="form-select" id="produto" name="produto">
                        <option value="">Selecione um produto</option>
                        @foreach ($prods as $prod)
                        <option value="{{$prod->id}}">{{$prod->nome}} {{$prod->marca}} - {{$prod->embalagem}}</option>
                        @endforeach
                    </select>
                    <label for="produto">Produto</label>
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
                        <th>Código</th>
                        <th>Venda</th>
                        <th>Status</th>
                        <th>Nome Produto</th>
                        <th>Qtd</th>
                        <th>Valor Produto</th>
                        <th>Desconto</th>
                        <th>Data & Hora</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rels as $rel)
                    <tr>
                        <td>{{$rel->id}}</td>
                        <td>{{$rel->venda_id}}</td>
                        <td @if($rel->status=='feito') style="color:blue; font-weight: bold;" @else @if($rel->status=='pago') style="color:green; font-weight: bold;" @else style="color:red; font-weight: bold;" @endif @endif>@if($rel->status=='feito') Feito @else @if($rel->status=='pago') Pago @else Cancelado @endif @endif</td>
                        <td>{{$rel->produto->nome}} {{$rel->produto->marca}} - {{$rel->produto->embalagem}}</td>
                        <td>{{$rel->qtd}}</td>
                        <td>{{ 'R$ '.number_format($rel->valor, 2, ',', '.')}}</td>
                        <td>{{ 'R$ '.number_format($rel->desconto, 2, ',', '.')}}</td>
                        <td>{{date("d/m/Y H:i", strtotime($rel->created_at))}}</td>
                    </tr>
                    @endforeach
                    @if($view=="filtro")
                    <tr>
                        <td colspan="5">TOTAIS</td>
                        <td>{{ 'R$ '.number_format($total_valor, 2, ',', '.')}}</td>
                        <td>{{ 'R$ '.number_format($total_desconto, 2, ',', '.')}}</td>
                        <td colspan="2">TOTAL GERAL: {{ 'R$ '.number_format($total_geral, 2, ',', '.')}}</td>
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