@extends('layouts.app', ["current"=>"estoque"])

@section('body')
@php
	$page = "Lista Compra";
@endphp
    <div class="card border">
        <div class="card-body">
            <a href="/relatorios/estoque" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
            <br/><br/>
            @if(session('mensagem'))
                <div class="alert @if(session('type')=="success") alert-success @else @if(session('type')=="warning") alert-warning @else @if(session('type')=="danger") alert-danger @else alert-info @endif @endif @endif alert-dismissible fade show" role="alert">
                    {{session('mensagem')}}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <h5 class="card-title">Listas de Compra</h5>
            <a type="button" class="float-button" href="/relatorios/listaCompras/nova" data-toggle="tooltip" data-placement="bottom" title="Nova Lista">
                <i class="material-icons blue md-60">add_circle</i>
            </a>
            @if(count($listaProds)==0)
                <div class="alert alert-danger" role="alert">
                    Sem listas cadastradas!
                </div>
            @else
            <div class="table-responsive-xl">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                    <tr>
                        <th>Código</th>
                        <th>Usuário</th>
                        <th>Criação</th>
                        <th>Produtos</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listaProds as $lista)
                    <tr>
                        <td>{{$lista->id}}</td>
                        <td>{{$lista->usuario}}</td>
                        <td>{{date("d/m/Y H:i", strtotime($lista->created_at))}}</td>
                        <td>
                            <!-- Button trigger modal -->
                            <button type="button" class="badge bg-info" data-bs-toggle="modal" data-bs-target="#exampleModal{{$lista->id}}">
                            Produtos
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal{{$lista->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Produtos da Lista {{$lista->id}} - {{date("d/m/Y", strtotime($lista->created_at))}}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <ul class="list-group">
                                        @php
                                            $qtd = $lista->produtos->count();
                                            $qtdEx = $lista->produtosExtras->count();
                                        @endphp
                                        @if ($qtd==0 && $qtdEx==0)
                                            <p>Todos os Itens desta lista foram removidos!</p>
                                        @else
                                            @if ($qtd!=0)
                                                @foreach ($lista->produtos as $produto)
                                                <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                    {{$produto->nome}} {{$produto->marca}} - {{$produto->embalagem}}
                                                    <a href="/relatorios/listaCompras/removerItem/{{$lista->id}}/{{$produto->id}}"><i class="material-icons red" data-toggle="tooltip" data-placement="bottom" title="Desvincular">remove_circle</i></a>
                                                </li>
                                                @endforeach
                                            @endif
                                            @if ($qtdEx!=0)
                                                @foreach ($lista->produtosExtras as $produto)
                                                <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                    {{$produto->nome}}
                                                    <a href="/relatorios/listaCompras/removerItemExtra/{{$lista->id}}/{{$produto->nome}}"><i class="material-icons red" data-toggle="tooltip" data-placement="bottom" title="Desvincular">remove_circle</i></a>
                                                </li>
                                                @endforeach
                                            @endif
                                        @endif
                                    </ul>
                                </div>
                                </div>
                            </div>
                            </div>
                        </td>
                        <td>
                            @if ($qtd==0 && $qtdEx==0)
                            @else
                            <a target="_blank" href="/relatorios/listaCompras/pdf/{{$lista->id}}" class="badge bg-success">Gerar PDF</a>
                            @endif
                            <button type="button" class="badge bg-danger" data-bs-toggle="modal" data-bs-target="#exampleModalDelete{{$lista->id}}"><i class="material-icons md-18">delete</i></button></td>
                            <!-- Modal -->
                            <div class="modal fade bd-example-modal-lg" id="exampleModalDelete{{$lista->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Lista Nº {{$lista->id}}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <h5>Tem certeza que deseja excluir essa lista?</h5>
                                            <p>Não será possivel reverter esta ação.</p>
                                            <a href="/relatorios/listaCompras/apagar/{{$lista->id}}" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="right" title="Inativar">Excluir</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-footer">
                {{ $listaProds->links() }}
            </div>
            </div>
            @endif
        </div>
    </div>
@endsection