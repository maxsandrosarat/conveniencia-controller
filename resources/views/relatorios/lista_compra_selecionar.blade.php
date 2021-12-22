@extends('layouts.app', ["current"=>"estoque"])

@section('body')
@php
	$page = "Lista Compra Selecionar";
@endphp
    <div class="card border">
        <div class="card-body">
            <a href="/relatorios/listaCompras" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
            <br/><br/>
            <h5 class="card-title">Lista de Produtos no Sistema com Estoque atual</h5>
            @if(count($prods)==0)
                <div class="alert alert-danger" role="alert">
                    Sem produtos cadastrados!
                </div>
            @else
            <div class="row justify-content-center">
                <div class="col-md-auto">
                    <form action="/relatorios/listaCompras" method="POST">
                        @csrf
                        <ul id="lista-produtos" class="list-group">
                        @foreach ($prods as $prod)
                            <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <input type="checkbox" id="produto{{$prod->id}}" class="form-check-input" name="produtos[]" value="{{$prod->id}}">
                                <label class="form-check-label" for="produto{{$prod->id}}">{{$prod->nome}} {{$prod->marca}} - {{$prod->embalagem}}</label>
                                <span class="badge bg-primary rounded-pill">{{$prod->estoque}}</span>
                            </li>
                        @endforeach
                        </ul>
                        <br/>
                        <div class="input-group mb-3">
                            <input id="input-prodEx" type="text" class="form-control" name="produtoExtra" placeholder="Adicionar Produto Extra">
                            <div class="input-group-append">
                                <button id="botao-add-prod" class="btn btn-success btn-sm" type="button" data-toggle="tooltip" data-placement="bottom" title="Adicionar Produto" onclick="adicionarProduto()"><i class="material-icons white">add_circle</i></button>
                            </div>
                        </div>
                        <br/>
                        <button type="submit" class="btn btn-primary btn-sn">Salvar</button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection