
@extends('layouts.app', ["current"=>"cadastros"])

@section('body')
@php
	$page = "Cadastro Formas de Pagamento";
@endphp
    <div class="card border">
        <div class="card-body">
            <a href="/cadastros" class="btn btn-success"data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
            <br/><br/>
            <h5 class="card-title">Lista de Formas de Pagamento - Total: {{count($formas)}}</h5>
            @if(session('mensagem'))
                <div class="alert @if(session('type')=="success") alert-success @else @if(session('type')=="warning") alert-warning @else @if(session('type')=="danger") alert-danger @else alert-info @endif @endif @endif alert-dismissible fade show" role="alert">
                    {{session('mensagem')}}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(count($formas)==0)
                <div class="alert alert-secondary" role="alert">
                    Sem formas de pagamento cadastradas!
                </div>
            @else
            <div class="table-responsive-xl">
                <table class="table table-striped table-ordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Id</th>
                            <th>Nome</th>
                            <th>Juros</th>
                            <th>Tipo Juros</th>
                            <th>Valor Juros</th>
                            <th>Ativo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($formas as $forma)
                        <tr>
                            <td>{{$forma->id}}</td>
                            <td>{{$forma->descricao}}</td>
                            <td>
                                @if($forma->juros==1)
                                    <b><i class="material-icons green" data-toggle="tooltip" data-placement="bottom" title="Ativo">add_task</i></b>
                                @else
                                    <b><i class="material-icons red" data-toggle="tooltip" data-placement="bottom" title="Inativo">remove_circle_outline</i></b>
                                @endif
                            </td>
                            <td>
                                @if($forma->tipo_juros==null)

                                @elseif($forma->tipo_juros=="fixo")
                                    <b>Fixo</b>
                                @else
                                    <b>Porcentagem (%)</b>
                                @endif
                            </td>
                            <td>
                                @if($forma->tipo_juros==null)
                                
                                @elseif($forma->tipo_juros=="fixo")
                                    <b>{{ 'R$ '.number_format($forma->valor_juros, 2, ',', '.')}}</b>
                                @else
                                    <b>{{$forma->valor_juros}} %</b>
                                @endif
                            </td>
                            <td>
                                @if($forma->ativo==1)
                                    <b><i class="material-icons green" data-toggle="tooltip" data-placement="bottom" title="Ativo">check_circle</i></b>
                                @else
                                    <b><i class="material-icons red" data-toggle="tooltip" data-placement="bottom" title="Inativo">highlight_off</i></b>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="badge bg-warning" data-bs-toggle="modal" data-bs-target="#exampleModal{{$forma->id}}" data-toggle="tooltip" data-placement="left" title="Editar">
                                    <i class="material-icons md-18">edit</i>
                                </button>
                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal{{$forma->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Editar Forma</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="/cadastros/pagamentoFormas/editar/{{$forma->id}}" method="POST">
                                                    @csrf
                                                    <div class="col-12 form-floating">
                                                        <input type="text" class="form-control" name="descricao" id="descricao" value="{{$forma->descricao}}" required>
                                                        <label for="descricao">Descrição (obrigatório)</label>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" role="switch" id="juros" name="juros" value="1" @if($forma->juros==1) checked @endif>
                                                            <label class="form-check-label" for="juros">Cobrar Juros (opcional)</label>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="form-floating">
                                                            <select class="form-select" id="tipoJuros" name="tipoJuros">
                                                                @if($forma->tipo_juros==null)
                                                                <option value="">Selecione</option>
                                                                <option value="fixo">Fixo</option>
                                                                <option value="porc">Porcentagem (%)</option>
                                                                @elseif($forma->tipo_juros=="fixo")
                                                                <option value="fixo">Fixo</option>
                                                                <option value="porc">Porcentagem (%)</option>
                                                                @else
                                                                <option value="porc">Porcentagem (%)</option>
                                                                <option value="fixo">Fixo</option>
                                                                @endif
                                                            </select>
                                                            <label for="tipoJuros">Tipo de Juros</label>
                                                        </div>
                                                        <div class="col-12 form-floating">
                                                            <input type="text" class="form-control" name="valorJuros" id="valorJuros" @if($forma->tipo_juros==null) placeholder="Valor Juros" @else value="{{$forma->valor_juros}}" @endif>
                                                            <label for="valorJuros">Valor Juros</label>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-outline-primary btn-sn">Cadastrar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if($forma->ativo==1)
                                    <a href="/cadastros/pagamentoFormas/ativar/{{$forma->id}}" type="button" class="badge bg-dark" data-toggle="tooltip" data-placement="right" title="Inativar"><i class="material-icons md-18 red">disabled_by_default</i></a>
                                @else
                                    <a href="/cadastros/pagamentoFormas/ativar/{{$forma->id}}" type="button" class="badge bg-dark" data-toggle="tooltip" data-placement="right" title="Ativar"><i class="material-icons md-18 green">check_box</i></a>
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
    <a type="button" class="float-button" data-bs-toggle="modal" data-bs-target="#exampleModal" data-toggle="tooltip" data-placement="bottom" title="Adicionar Nova Forma">
        <i class="material-icons blue md-60">add_circle</i>
    </a>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cadastro de Forma de Pagamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/cadastros/pagamentoFormas" method="POST">
                        @csrf
                        <div class="col-12 form-floating">
                            <input type="text" class="form-control" name="descricao" id="descricao" placeholder="Descrição (obrigatório)" required>
                            <label for="descricao">Descrição (obrigatório)</label>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="juros" name="juros" value="1">
                                <label class="form-check-label" for="juros">Cobrar Juros (opcional)</label>
                            </div>
                        </div>
                        <div>
                            <div class="form-floating">
                                <select class="form-select" id="tipoJuros" name="tipoJuros">
                                    <option value="">Selecione</option>
                                    <option value="fixo">Fixo</option>
                                    <option value="porc">Porcentagem (%)</option>
                                </select>
                                <label for="tipoJuros">Tipo de Juros</label>
                            </div>
                            <div class="col-12 form-floating">
                                <input type="text" class="form-control" name="valorJuros" id="valorJuros" placeholder="Valor Juros">
                                <label for="valorJuros">Valor Juros</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-outline-primary btn-sn">Cadastrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection