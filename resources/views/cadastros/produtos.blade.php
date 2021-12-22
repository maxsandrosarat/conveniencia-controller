@extends('layouts.app', ["current"=>"cadastros"])

@section('body')
@php
	$page = "Cadastro Produtos";
@endphp
    <div class="card border">
        <div class="card-body">
            <a href="/cadastros" class="btn btn-success"data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
            <br/><br/>
            <div class="row">
                <div class="col" style="text-align: left">
                    <h5 class="card-title">Lista de Produtos - Total: {{count($produtos)}}</h5>
                </div>
                <div class="col" style="text-align: right">
                    @if(count($produtos)>0)
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
            @if(count($produtos)==0)
                <div class="alert alert-secondary" role="alert">
                    Sem produtos cadastrados!
                </div>
            @else
            <div class="collapse" id="collapseExample">
                <div class="card card-body">
                        <div class="row">
                            <div class="col-sm-11" style="text-align: left">
                                <form class="row gy-2 gx-3 align-items-center" method="GET" action="/cadastros/produtos/filtro">
                                    @csrf
                                    <div class="col-auto form-floating">
                                        <input class="form-control mr-sm-2" id="id" type="number" placeholder="Id" name="id">
                                        <label for="id">Id</label>
                                    </div>
                                    <div class="col-auto form-floating">
                                        <input class="form-control mr-sm-2" type="text" id="nome" placeholder="Nome" name="nome">
                                        <label for="nome">Nome</label>
                                    </div>
                                    <div class="col-auto form-floating">
                                        <input class="form-control mr-sm-2" type="number" id="estoque" placeholder="Estoque (<=)" name="estoque">
                                        <label for="estoque">Estoque (<=)</label>
                                    </div>
                                    <div class="col-auto form-floating">
                                        <select class="form-select" id="categoria" name="categoria">
                                            <option value="">Selecione</option>
                                            @foreach ($categorias as $categoria)
                                                @if ($categoria->ativo==0)
                                                <option value="{{$categoria->id}}" style="color:red;">{{$categoria->nome}}</option>
                                                @else
                                                <option value="{{$categoria->id}}">{{$categoria->nome}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <label for="categoria">Categoria</label>
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
            <h5>Exibindo {{$produtos->count()}} de {{$produtos->total()}} de Produto(s) ({{$produtos->firstItem()}} a {{$produtos->lastItem()}})</h5>
            <hr/>
            <div class="table-responsive-xl">
                @foreach ($produtos as $produto)
                    <a class="fill-div" data-bs-toggle="modal" data-bs-target="#exampleModal{{$produto->id}}">
                        <div id="my-div" class="bd-callout 
                        @if($produto->ativo==0)
                            bd-callout-secondary
                        @else
                            @if($produto->estoque>3) 
                                bd-callout-success 
                            @else 
                                @if($produto->estoque>=2 && $produto->estoque<=3) 
                                    bd-callout-info 
                                @else 
                                    @if($produto->estoque==1) 
                                        bd-callout-warning 
                                    @else 
                                        @if($produto->estoque==0) 
                                            bd-callout-danger 
                                        @endif 
                                    @endif 
                                @endif 
                            @endif
                        @endif">
                            <h4>{{$produto->id}} - {{$produto->nome}} {{$produto->marca}} ({{$produto->embalagem}}) - {{$produto->categoria->nome}} - Estoque: {{$produto->estoque}}</h4>
                            <p>{{ 'R$ '.number_format($produto->preco_atual, 2, ',', '.')}}</p>
                        </div>
                    </a>
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal{{$produto->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Produto ID: <b>{{$produto->id}}</b></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="text-center">
                                        @if($produto->foto!="") <img src="/storage/{{$produto->foto}}" alt="foto_produto" style="width: 100%"> @else <i class="material-icons md-48">no_photography</i> @endif
                                    </div>
                                    <hr/>
                                    <ul class="list-group">
                                        <li class="list-group-item">Código de Barras: <b>{{$produto->codigo_barras}}</b></li>
                                        <li class="list-group-item">Nome: <b>{{$produto->nome}}</b></li>
                                        <li class="list-group-item">Embalagem: <b>{{$produto->embalagem}}</b></li>
                                        <li class="list-group-item">Marca: <b>{{$produto->marca}}</b></li>
                                        <li class="list-group-item">Categoria: <b>{{$produto->categoria->nome}}</b></li>
                                        <li class="list-group-item">Preço Atual: <b>{{ 'R$ '.number_format($produto->preco_atual, 2, ',', '.')}}</b> <button class="badge bg-secondary rounded-pill" data-bs-toggle="modal" data-bs-target="#modalPrecos{{$produto->id}}">Histórico de Preços</button></li>
                                        {{--  Modal Preços  --}}
                                        <div class="modal fade" id="modalPrecos{{$produto->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Histórico de Preços</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h5>{{$produto->nome}} {{$produto->marca}} ({{$produto->embalagem}})</h5>
                                                        <div class="table-responsive-xl">
                                                            <table class="table table-striped table-ordered table-hover">
                                                                <thead class="table-dark">
                                                                    <tr>
                                                                        <th>Preço</th>
                                                                        <th>Data</th>
                                                                        <th>Usuário</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($produto->precos as $preco)
                                                                    <tr>
                                                                        <td>{{ 'R$ '.number_format($preco->preco, 2, ',', '.')}}</td>
                                                                        <td>{{date("d/m/Y H:i", strtotime($preco->created_at))}}</td>
                                                                        <td>{{$preco->usuario}}</td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <li class="list-group-item">Estoque: <b>{{$produto->estoque}}</b> <button class="badge bg-secondary rounded-pill" data-bs-toggle="modal" data-bs-target="#modalEntradas{{$produto->id}}">Histórico de Entradas</button></li>
                                        {{--  Modal Entradas  --}}
                                        <div class="modal fade" id="modalEntradas{{$produto->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Histórico de Entradas</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h5>{{$produto->nome}} {{$produto->marca}} ({{$produto->embalagem}})</h5>
                                                        <div class="table-responsive-xl">
                                                            <table class="table table-striped table-ordered table-hover">
                                                                <thead class="table-dark">
                                                                    <tr>
                                                                        <th>Quantidade</th>
                                                                        <th>Custo</th>
                                                                        <th>Data</th>
                                                                        <th>Usuário</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($produto->entradas as $entrada)
                                                                    <tr>
                                                                        <td>{{$entrada->quantidade_entrada}}</td>
                                                                        <td>{{ 'R$ '.number_format($entrada->custo, 2, ',', '.')}}</td>
                                                                        <td>{{date("d/m/Y H:i", strtotime($entrada->created_at))}}</td>
                                                                        <td>{{$entrada->usuario}}</td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Ativo:
                                            @if($produto->ativo==1)
                                                <span class="badge bg-light rounded-pill"><b><i class="material-icons green">check_circle</i></b></span>
                                            @else
                                                <span class="badge bg-light rounded-pill"><b><i class="material-icons red">highlight_off</i></b></span>
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="badge bg-warning" data-bs-toggle="modal" data-bs-target="#modalEdit{{$produto->id}}" data-toggle="tooltip" data-placement="left" title="Editar">
                                        <i class="material-icons md-18">edit</i>
                                    </button>
                                    {{--  Modal Editar  --}}
                                    <div class="modal fade" id="modalEdit{{$produto->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Editar Produto</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form class="row g-3" action="/cadastros/produtos/editar/{{$produto->id}}" method="POST">
                                                        @csrf
                                                        <div class="col-12 form-floating">
                                                            <input type="number" class="form-control" name="codigoBarras" id="codigoBarras{{$produto->id}}" @if($produto->codigo_barras!="") value="{{$produto->codigo_barras}}" @else placeholder="Código de Barras (opcional)" @endif onblur="validarCodBarras('codigoBarras{{$produto->id}}');">
                                                            <label for="codigoBarras{{$produto->id}}">Código de Barras (opcional)</label>
                                                        </div>
                                                        <div class="col-12 form-floating">
                                                            <input type="text" class="form-control" name="nome" id="nome" value="{{$produto->nome}}" required>
                                                            <label for="nome">Nome do Produto (obrigatório)</label>
                                                        </div>
                                                        <div class="col-12 form-floating">
                                                            <input type="text" class="form-control" name="embalagem" id="embalagem" value="{{$produto->embalagem}}" required>
                                                            <label for="embalagem">Embalagem do Produto (obrigatório)</label>
                                                        </div>
                                                        <div class="col-12 form-floating">
                                                            <input type="text" class="form-control" name="marca" id="marca" @if($produto->marca!="") value="{{$produto->marca}}" @else placeholder="Marca do Produto (opcional)" @endif>
                                                            <label for="marca">Marca do Produto (opcional)</label>
                                                        </div>
                                                        <div class="col-12 form-floating">
                                                            <input type="text" class="form-control" name="preco" id="preco{{$produto->id}}" value="{{$produto->preco_atual}}" onblur="validarPreco('preco{{$produto->id}}');" required>
                                                            <label for="preco{{$produto->id}}">Preço do Produto</label>
                                                        </div>
                                                        <div class="col-12 form-floating">
                                                            <input type="number" class="form-control" name="estoque" id="estoque" value="{{$produto->estoque}}" disabled>
                                                            <label for="estoque">Estoque do Produto</label>
                                                        </div>
                                                        <div class="form-floating">
                                                            <select class="form-select" id="categoria" name="categoria" required>
                                                                <option value="{{$produto->categoria->id}}">{{$produto->categoria->nome}}</option>
                                                                @foreach ($categorias as $categoria)
                                                                    @if ($categoria->id == $produto->categoria->id)
                                                                    @else
                                                                        <option value="{{$categoria->id}}">{{$categoria->nome}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                            <label for="categoria">Categoria (obrigatório)</label>
                                                        </div>
                                                        <div class="col-12 input-group mb-3">
                                                            <label for="foto" class="input-group-text" data-toggle="tooltip" data-placement="bottom" title="Adicionar Foto (opcional)"><i class="material-icons blue md-24">add_photo_alternate</i></label>
                                                            <input class="form-control" type="file" id="foto" name="foto" accept=".svg,.jpg,.png,.jpeg">
                                                        </div>
                                                        <b style="font-size: 80%;">Aceito apenas Imagens SVG, JPG e PNG (".svg", ".jpg" e ".png")</b>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-outline-primary btn-sn">Salvar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="badge bg-primary" data-bs-toggle="modal" data-bs-target="#modalEntrada{{$produto->id}}" data-toggle="tooltip" data-placement="left" title="Editar">
                                        <i class="material-icons md-18">add_shopping_cart</i>
                                    </button>
                                    
                                    <div class="modal fade" id="modalEntrada{{$produto->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Entrada - Produto: {{$produto->nome}} {{$produto->marca}} ({{$produto->embalagem}})</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form class="row g-3" action="/cadastros/produtos/entrada/{{$produto->id}}" method="POST">
                                                        @csrf
                                                        <div class="col-12 form-floating">
                                                            <input type="number" class="form-control" name="qtd" id="qtd" placeholder="Quantidade (obrigatório)" required>
                                                            <label for="qtd">Quantidade (obrigatório)</label>
                                                        </div>
                                                        <div class="col-12 form-floating">
                                                            <input type="text" class="form-control" name="custo" id="custo{{$produto->id}}" placeholder="Custo do Produto (obrigatório)" onblur="validarPreco('custo{{$produto->id}}');" required>
                                                            <label for="custo{{$produto->id}}">Custo do Produto (obrigatório)</label>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-outline-primary btn-sn">Salvar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($produto->ativo==1)
                                        <a href="/cadastros/produtos/ativar/{{$produto->id}}" class="badge bg-secondary" data-toggle="tooltip" data-placement="right" title="Inativar"><i class="material-icons md-18 red">disabled_by_default</i></a>
                                    @else
                                        <a href="/cadastros/produtos/ativar/{{$produto->id}}" class="badge bg-secondary" data-toggle="tooltip" data-placement="right" title="Ativar"><i class="material-icons md-18 green">check_box</i></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            <div class="card-footer">
                {{ $produtos->links() }}
            </div>
        </div>
            @endif
        </div>
    </div>
    
    <a type="button" class="float-button" data-bs-toggle="modal" data-bs-target="#exampleModal" data-toggle="tooltip" data-placement="bottom" title="Adicionar Novo produtoiço">
        <i class="material-icons blue md-60">add_circle</i>
    </a>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cadastro de Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3" action="/cadastros/produtos" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-12 form-floating">
                            <input type="number" class="form-control" name="codigoBarras" id="codigoBarras0" placeholder="Código de Barras (opcional)" onblur="validarCodBarras('codigoBarras0');">
                            <label for="codigoBarras0">Código de Barras (opcional)</label>
                        </div>
                        <div class="col-12 form-floating">
                            <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome do Produto (obrigatório)" required>
                            <label for="nome">Nome do Produto (obrigatório)</label>
                        </div>
                        <div class="col-12 form-floating">
                            <input type="text" class="form-control" name="embalagem" id="embalagem" placeholder="Embalagem do Produto (obrigatório)" required>
                            <label for="embalagem">Embalagem do Produto (obrigatório)</label>
                        </div>
                        <div class="col-12 form-floating">
                            <input type="text" class="form-control" name="marca" id="marca" placeholder="Marca do Produto (opcional)">
                            <label for="marca">Marca do Produto (opcional)</label>
                        </div>
                        <div class="col-12 form-floating">
                            <input type="text" class="form-control" name="preco" id="preco0" placeholder="Preço do Produto (obrigatório)" onblur="validarPreco('preco0');" required>
                            <label for="preco0">Preço do Produto (obrigatório)</label>
                        </div>
                        {{--  <div class="col-12 form-floating">
                            <input type="number" class="form-control" name="estoque" id="estoque" placeholder="Estoque do Produto (obrigatório)" required>
                            <label for="estoque">Estoque do Produto (obrigatório)</label>
                        </div>  --}}
                        <div class="form-floating">
                            <select class="form-select" id="categoria" name="categoria" required>
                                <option value="">Selecione</option>
                                @foreach ($categorias as $categoria)
                                <option value="{{$categoria->id}}">{{$categoria->nome}}</option>
                                @endforeach
                                </select>
                            <label for="categoria">Categoria (obrigatório)</label>
                        </div>
                        <div class="col-12 input-group mb-3">
                            <label for="foto" class="input-group-text" data-toggle="tooltip" data-placement="bottom" title="Adicionar Foto (opcional)"><i class="material-icons blue md-24">add_photo_alternate</i></label>
                            <input class="form-control" type="file" id="foto" name="foto" accept=".svg,.jpg,.png,.jpeg">
                        </div>
                        <b style="font-size: 80%;">Aceito apenas Imagens SVG, JPG e PNG (".svg", ".jpg" e ".png")</b>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-outline-primary btn-sn">Cadastrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection