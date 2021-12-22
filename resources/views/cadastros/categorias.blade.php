@extends('layouts.app', ["current"=>"cadastros"])

@section('body')
@php
	$page = "Cadastro Categorias";
@endphp
    <div class="card border">
        <div class="card-body">
            <a href="/cadastros" class="btn btn-success"data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
            <br/><br/>
            <h5 class="card-title">Lista de Categorias - Total: {{count($categorias)}}</h5>
            @if(session('mensagem'))
                <div class="alert @if(session('type')=="success") alert-success @else @if(session('type')=="warning") alert-warning @else @if(session('type')=="danger") alert-danger @else alert-info @endif @endif @endif alert-dismissible fade show" role="alert">
                    {{session('mensagem')}}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(count($categorias)==0)
                <div class="alert alert-secondary" role="alert">
                    Sem Categorias Cadastradas!
                </div>
            @else
            <div class="table-responsive-xl">
                <table class="table table-striped table-ordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Id</th>
                            <th>Nome</th>
                            <th>Ativo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categorias as $categoria)
                        <tr>
                            <td>{{$categoria->id}}</td>
                            <td>{{$categoria->nome}}</td>
                            <td>
                                @if($categoria->ativo==1)
                                    <b><i class="material-icons green" data-toggle="tooltip" data-placement="bottom" title="Ativo">check_circle</i></b>
                                @else
                                    <b><i class="material-icons red" data-toggle="tooltip" data-placement="bottom" title="Inativo">highlight_off</i></b>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="badge bg-warning" data-bs-toggle="modal" data-bs-target="#exampleModal{{$categoria->id}}" data-toggle="tooltip" data-placement="left" title="Editar">
                                    <i class="material-icons md-18">edit</i>
                                </button>
                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal{{$categoria->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Editar Categoria</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="/cadastros/categorias/editar/{{$categoria->id}}" method="POST">
                                                    @csrf
                                                    <div class="col-12 form-floating">
                                                        <input type="text" class="form-control" name="nome" id="nome" value="{{$categoria->nome}}" required>
                                                        <label for="nome">Nome</label>
                                                    </div>                         
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-outline-primary btn-sn">Salvar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if($categoria->ativo==1)
                                    <a href="/cadastros/categorias/ativar/{{$categoria->id}}" type="button" class="badge bg-dark" data-toggle="tooltip" data-placement="right" title="Inativar"><i class="material-icons md-18 red">disabled_by_default</i></a>
                                @else
                                    <a href="/cadastros/categorias/ativar/{{$categoria->id}}" type="button" class="badge bg-dark" data-toggle="tooltip" data-placement="right" title="Ativar"><i class="material-icons md-18 green">check_box</i></a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
    <a type="button" class="float-button" data-bs-toggle="modal" data-bs-target="#exampleModal" data-toggle="tooltip" data-placement="bottom" title="Adicionar Nova Função">
        <i class="material-icons blue md-60">add_circle</i>
    </a>
    <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Cadastro de Categoria</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="card border">
                                <div class="card-body">
                                    <form action="/cadastros/categorias" method="POST">
                                        @csrf
                                        <div class="col-12 form-floating">
                                            <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome" required>
                                            <label for="nome">Nome</label>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-outline-primary btn-sn">Cadastrar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection