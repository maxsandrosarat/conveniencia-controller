@extends('layouts.app', ["current"=>"cadastros"])

@section('body')
@php
	$page = "Cadastro Clientes";
@endphp
    <div class="card border">
        <div class="card-body">
            <a href="/cadastros" class="btn btn-success"data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
            <br/><br/>
            <div class="row">
                <div class="col" style="text-align: left">
                    <h5 class="card-title">Lista de Clientes - Total: {{count($clientes)}}</h5>
                </div>
                <div class="col" style="text-align: right">
                    @if(count($clientes)>0)
                    <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" data-bs-toggle="tooltip" data-placement="bottom" title="Filtro">
                        <i class="material-icons blue md-24">filter_alt</i>
                    </button>
                    @endif
                </div>
            </div>
            @if(session('mensagem'))
                <div class="alert @if(session('type')=="success") alert-success @else @if(session('type')=="warning") alert-warning @else @if(session('type')=="danger") alert-danger @else alert-info @endif @endif @endif alert-dismissible fade show" role="alert">
                    {{session('mensagem')}}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(count($clientes)==0)
                <div class="alert alert-secondary" role="alert">
                    Sem Clientes cadastrados!
                </div>
            @else
            <div class="collapse" id="collapseExample">
                <div class="card card-body">
                    <div class="row">
                        <div class="col-sm-11" style="text-align: left">
                            <form class="row gy-2 gx-3 align-items-center" method="GET" action="/cadastros/clientes/filtro">
                                @csrf
                                <div class="col-auto form-floating">
                                    <input class="form-control mr-sm-2" type="text" id="nome" placeholder="Nome" name="nome">
                                    <label for="nome">Nome</label>
                                </div>
                                <div class="col-auto form-floating">
                                    <select class="form-select" id="ativo" name="ativo">
                                        <option value="1">Ativo</option>
                                        <option value="0">Inativo</option>
                                    </select>
                                    <label for="ativo">Status</label>
                                </div>  
                                <div class="col-auto">
                                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Filtrar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            <h5>Exibindo {{$clientes->count()}} de {{$clientes->total()}} de Cliente(s) ({{$clientes->firstItem()}} a {{$clientes->lastItem()}})</h5>
            <div class="table-responsive-xl">
                <table class="table table-striped table-ordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Id</th>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>Nascimento</th>
                            <th>Telefones</th>
                            <th>Endereço</th>
                            <th>Ativo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clientes as $cliente)
                        <tr>
                            <td>{{$cliente->id}}</td>
                            <td>{{$cliente->nome}}</td>
                            <td>{{$cliente->cpf}}</td>
                            <td>@if($cliente->dtn!="") {{date("d/m/Y", strtotime($cliente->dtn))}} @endif</td>
                            <td>
                                @if($cliente->tel1!="" && $cliente->tel2!="")
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModalTel{{$cliente->id}}">
                                    <i class="material-icons blue">add_ic_call</i>
                                </button>
    
                                <!-- Modal -->
                                <div class="modal fade" id="exampleModalTel{{$cliente->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Telefones - Cliente: {{$cliente->nome}}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        @if($cliente->tel1=="" && $cliente->tel2=="")
                                            <div class="alert alert-warning" role="alert">
                                                Sem telefones cadastrados!
                                            </div>
                                        @else
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Números</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{$cliente->tel1}}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{$cliente->tel2}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                    </div>
                                    </div>
                                </div>
                                </div>
                                @endif
                            </td>
                            <td>
                                @if($cliente->rua!="")
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModalEnd{{$cliente->id}}">
                                    <i class="material-icons green">location_on</i>
                                </button>
    
                                <!-- Modal -->
                                <div class="modal fade" id="exampleModalEnd{{$cliente->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Endereço - Cliente: {{$cliente->nome}}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        @if($cliente->rua=="")
                                            <div class="alert alert-warning" role="alert">
                                                Sem endereço cadastrado!
                                            </div>
                                        @else
                                        <div class="table-responsive">
                                            <table class="table table-striped table-ordered table-hover">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Endereço</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>{{$cliente->rua}}, {{$cliente->numero}} @if($cliente->complemento!="") ({{$cliente->complemento}}) @endif - {{$cliente->bairro}} -  {{$cliente->cidade}} - {{$cliente->uf}} - {{$cliente->cep}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                    </div>
                                    </div>
                                </div>
                                </div>
                                @endif
                            </td>
                            <td>
                                @if($cliente->ativo==1)
                                    <b><i class="material-icons green" data-toggle="tooltip" data-placement="bottom" title="Ativo">check_circle</i></b>
                                @else
                                    <b><i class="material-icons red" data-toggle="tooltip" data-placement="bottom" title="Inativo">highlight_off</i></b>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="badge bg-warning" data-bs-toggle="modal" data-bs-target="#exampleModal{{$cliente->id}}" data-toggle="tooltip" data-placement="left" title="Editar">
                                    <i class="material-icons md-18">edit</i>
                                </button>
                                <!-- Modal Editar -->
                                <div class="modal fade" id="exampleModal{{$cliente->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Editar Cliente</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="card border">
                                                    <div class="card-body">
                                                        <form action="/cadastros/clientes/editar/{{$cliente->id}}" method="POST">
                                                            @csrf
                                                            <div class="col-12 form-floating">
                                                                <input type="text" class="form-control" name="nome" id="nome" value="{{$cliente->nome}}" required>
                                                                <label for="nome">Nome (obrigatório)</label>
                                                            </div>
                                                            <div class="col-12 form-floating">
                                                                <input type="text" class="form-control" name="cpf" id="cpf" @if($cliente->cpf=="") placeholder="CPF (opcional)" @else value="{{$cliente->cpf}}" @endif onblur="formatarCpf();">
                                                                <label for="cpf">CPF (opcional)</label>
                                                            </div>
                                                            <div class="col-12 form-floating">
                                                                <input type="date" class="form-control" name="dtn" id="dtn" @if($cliente->dtn=="") placeholder="Nascimento (opcional)" @else value="{{$cliente->dtn}}" @endif>
                                                                <label for="dtn">Nascimento (opcional)</label>
                                                            </div>
                                                            <hr/>
                                                            <h3>Telefones (opcional)</h3>
                                                            <div class="col-12 form-floating">
                                                                <input type="text" class="form-control" name="tel1" id="telefone01" @if($cliente->tel1=="") placeholder="Telefone 1" @else value="{{$cliente->tel1}}" @endif onblur="formataNumeroTelefone('01')">
                                                                <label for="telefone01">Telefone 1</label>
                                                            </div>
                                                            <div class="col-12 form-floating">
                                                                <input type="text" class="form-control" name="tel2" id="telefone02" @if($cliente->tel2=="") placeholder="Telefone 2" @else value="{{$cliente->tel2}}" @endif onblur="formataNumeroTelefone('02')">
                                                                <label for="telefone02">Telefone 2</label>
                                                            </div>
                                                            <hr/>
                                                            <h3>Endereço (opcional)</h3>
                                                            <h6><b>Caso saiba seu CEP, digite (apenas números)</b></h6>
                                                            <div class="col-12 form-floating">
                                                                <input class="form-control" name="cep" type="number" id="cep{{$cliente->id}}" size="10" maxlength="9" @if($cliente->cep=="") placeholder="CEP" @else value="{{$cliente->cep}}" @endif onblur="pesquisacep(this.value, {{$cliente->id}});"/>
                                                                <label>CEP</label>
                                                            </div>
                                                            <div class="col-12 form-floating">
                                                                <input class="form-control" name="rua" type="text" id="rua{{$cliente->id}}" size="60" @if($cliente->rua=="") placeholder="Rua" @else value="{{$cliente->rua}}" @endif/>
                                                                <label>Rua</label>
                                                            </div>
                                                            <div class="col-12 form-floating">
                                                                <input class="form-control" name="bairro" type="text" id="bairro{{$cliente->id}}" size="40" @if($cliente->bairro=="") placeholder="Bairro" @else value="{{$cliente->bairro}}" @endif/>
                                                                <label>Bairro</label>
                                                            </div>
                                                            <div class="col-12 form-floating">
                                                                <input class="form-control" name="cidade" type="text" id="cidade{{$cliente->id}}" size="40" @if($cliente->cidade=="") placeholder="Cidade" @else value="{{$cliente->cidade}}" @endif/>
                                                                <label>Cidade</label>
                                                            </div>
                                                            <div class="col-auto form-floating">
                                                                <input class="form-control" name="uf" type="text" id="uf{{$cliente->id}}" size="2" @if($cliente->uf=="") placeholder="Estado" @else value="{{$cliente->uf}}" @endif/>
                                                                <label>Estado</label>
                                                            </div>
                                                            <input class="form-control" name="ibge" type="hidden" id="ibge{{$cliente->id}}" size="8" />
                                                            <div class="col-12 form-floating">
                                                                <input class="form-control" type="number" name="numero" id="numero{{$cliente->id}}" size="5" @if($cliente->numero=="") placeholder="Número" @else value="{{$cliente->numero}}" @endif>
                                                                <label for="numero">Número</label>
                                                            </div>
                                                            <div class="col-12 form-floating">
                                                                <input class="form-control" type="text" name="complemento" id="complemento{{$cliente->id}}" size="60" @if($cliente->complemento=="") placeholder="Complemento" @else value="{{$cliente->complemento}}" @endif>
                                                                <label for="complemento">Complemento</label>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-outline-primary btn-sn">Salvar</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if($cliente->ativo==1)
                                    <a href="/cadastros/clientes/ativar/{{$cliente->id}}" type="button" class="badge bg-dark" data-toggle="tooltip" data-placement="right" title="Inativar"><i class="material-icons md-18 red">disabled_by_default</i></a>
                                @else
                                    <a href="/cadastros/clientes/ativar/{{$cliente->id}}" type="button" class="badge bg-dark" data-toggle="tooltip" data-placement="right" title="Ativar"><i class="material-icons md-18 green">check_box</i></a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-footer">
                    {{ $clientes->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
    <a type="button" class="float-button" data-bs-toggle="modal" data-bs-target="#exampleModal" data-toggle="tooltip" data-placement="bottom" title="Adicionar Nova Função">
        <i class="material-icons blue md-60">add_circle</i>
    </a>
    <!-- Modal Cadastro -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Cadastro de Cliente</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="card border">
                                <div class="card-body">
                                    <form action="/cadastros/clientes" method="POST">
                                        @csrf
                                        <div class="col-12 form-floating">
                                            <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome (obrigatório)" required>
                                            <label for="nome">Nome (obrigatório)</label>
                                        </div>
                                        <div class="col-12 form-floating">
                                            <input type="text" class="form-control" name="cpf" id="cpf" placeholder="CPF (opcional) " onblur="formatarCpf();">
                                            <label for="cpf">CPF (opcional)</label>
                                        </div>
                                        <div class="col-12 form-floating">
                                            <input type="date" class="form-control" name="dtn" id="dtn" placeholder="Nascimento (opcional)">
                                            <label for="dtn">Nascimento (opcional)</label>
                                        </div>
                                        <hr/>
                                        <h3>Telefones (opcional)</h3>
                                        <div class="col-12 form-floating">
                                            <input type="text" class="form-control" name="tel1" id="telefone01" placeholder="Telefone 1" onblur="formataNumeroTelefone('01')">
                                            <label for="telefone01">Telefone 1</label>
                                        </div>
                                        <div class="col-12 form-floating">
                                            <input type="text" class="form-control" name="tel2" id="telefone02" placeholder="Telefone 2" onblur="formataNumeroTelefone('02')">
                                            <label for="telefone02">Telefone 2</label>
                                        </div>
                                        <hr/>
                                        <h3>Endereço (opcional)</h3>
                                        <h6><b>Caso saiba seu CEP, digite (apenas números) e em seguida os campos serão autocompletados</b></h6>
                                        <div class="col-12 form-floating">
                                            <input class="form-control" name="cep" type="number" id="cep0" value="" size="10" maxlength="9" onblur="pesquisacep(this.value, 0);" placeholder="CEP"/>
                                            <label>CEP</label>
                                        </div>
                                        <div class="col-12 form-floating">
                                            <input class="form-control" name="rua" type="text" id="rua0" size="60" placeholder="Rua"/>
                                            <label>Rua</label>
                                        </div>
                                        <div class="col-12 form-floating">
                                            <input class="form-control" name="bairro" type="text" id="bairro0" size="40" placeholder="Bairro"/>
                                            <label>Bairro</label>
                                        </div>
                                        <div class="col-12 form-floating">
                                            <input class="form-control" name="cidade" type="text" id="cidade0" size="40" placeholder="Cidade"/>
                                            <label>Cidade</label>
                                        </div>
                                        <div class="col-auto form-floating">
                                            <input class="form-control" name="uf" type="text" id="uf0" size="2" placeholder="Estado"/>
                                            <label>Estado</label>
                                        </div>
                                        <input class="form-control" name="ibge" type="hidden" id="ibge0" size="8" />
                                        <div class="col-12 form-floating">
                                            <input class="form-control" type="number" name="numero" id="numero0" size="5" placeholder="Número">
                                            <label for="numero">Número</label>
                                        </div>
                                        <div class="col-12 form-floating">
                                            <input class="form-control" type="text" name="complemento" id="complemento0" size="60" placeholder="Complemento">
                                            <label for="complemento">Complemento</label>
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