@extends('layouts.app', ["current"=>"relatorios"])

@section('body')
@php
	$page = "Relatório Entradas";
@endphp
    <div class="card border">
        <div class="card-body">
            <a href="/relatorios/estoque" class="btn btn-success"data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
            <br/><br/>
            <h5 class="card-title">Relatório de Entradas</h5>
            @if(count($rels)==0)
                    <div class="alert alert-dark" role="alert">
                        @if($view=="inicial")
                        Sem movimentos cadastrados!
                        @else @if($view=="filtro")
                        Sem resultados da busca!
                        <a href="/relatorios/estoque/entradas" class="btn btn-success">Voltar</a>
                        @endif
                        @endif
                    </div>
            @else
            <div class="card border">
            <h5>Filtros: </h5>
            <form class="row gy-2 gx-3 align-items-center" method="GET" action="/relatorios/estoque/entradas/filtro">
                @csrf
                <div class="col-auto form-floating">
                    <select class="form-select" id="produto" name="produto">
                        <option value="">Selecione um produto</option>
                        @foreach ($prods as $prod)
                        <option value="{{$prod->id}}">{{$prod->nome}} {{$prod->marca}} - {{$prod->embalagem}} </option>
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
                        <th>Nome Produto</th>
                        <th>Custo</th>
                        <th>Qtd</th>
                        <th>Usuário</th>
                        <th>Data & Hora</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rels as $rel)
                    <tr>
                        <td>{{$rel->id}}</td>
                        <td>{{$rel->produto->nome}} {{$rel->produto->marca}} - {{$rel->produto->embalagem}} </td>
                        <td>{{ 'R$ '.number_format($rel->custo, 2, ',', '.')}}</td>
                        <td>{{$rel->quantidade_entrada}}</td>
                        <td>{{$rel->usuario}}</td>
                        <td>{{date("d/m/Y H:i", strtotime($rel->created_at))}}</td>
                    </tr>
                    @endforeach
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