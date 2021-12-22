@extends('layouts.app', ["current"=>"vendas"])

@section('body')
@php
	$page = "Vendas";
@endphp
    <div class="card border">
        <div class="card-body">
            <a href="/home" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
            <div class="row">
                <div class="col" style="text-align: left">
                    <hr/>
                    <h5>Exibindo {{$vendas->count()}} de {{$vendas->total()}} de Venda(s) ({{$vendas->firstItem()}} a {{$vendas->lastItem()}})</h5>
                </div>
                <div class="col" style="text-align: right">
                    @if(count($vendas)>0)
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
            @if(count($vendas)==0)
                <div class="alert alert-secondary" role="alert">
                    Sem vendas cadastradas!
                </div>
            @else
            <div class="collapse" id="collapseExample">
                <div class="card card-body">
                        <div class="row">
                            <div class="col-sm-11" style="text-align: left">
                                <form class="row gy-2 gx-3 align-items-center" method="GET" action="/vendas/filtro">
                                    @csrf
                                    <div class="col-2">
                                        <h5>Data</h5>
                                        <div class="col-auto form-floating">
                                            <input class="form-control mr-sm-2" type="date" placeholder="Entre / A partir" id="dataInicio" name="dataInicio">
                                            <label for="dataInicio">Entre / A partir</label>
                                        </div>
                                        <div class="col-auto form-floating">
                                            <input class="form-control mr-sm-2" type="date" placeholder="E / Até" id="dataFim" name="dataFim">
                                            <label for="dataFim">E / Até</label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-auto form-floating">
                                            <select class="form-select" id="cliente" name="cliente">
                                                <option value="">Selecione</option>
                                                @foreach ($clientes as $cliente)
                                                <option value="{{$cliente->id}}" @if($cliente->ativo==false) style="color: red;" @endif>{{$cliente->nome}} @if($cliente->cpf!="") ({{$cliente->cpf}}) @endif</option>
                                                @endforeach
                                            </select>
                                            <label for="cliente">Cliente</label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="col-auto form-floating">
                                            <select class="form-select" id="pagamento" name="pagamento">
                                                <option value="">Selecione</option>
                                                @foreach ($pagamentos as $pagamento)
                                                <option value="{{$pagamento->id}}" @if($pagamento->ativo==false) style="color: red;" @endif>{{$pagamento->descricao}}</option>
                                                @endforeach
                                            </select>
                                            <label for="pagamento">Forma Pagamento</label>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="col-auto form-floating">
                                            <select class="form-select" id="status" name="status">
                                                <option value="">Selecione</option>
                                                @foreach ($status as $st)
                                                    @if($st->status=="feita")
                                                    <option value="{{$st->status}}" style="color: blue;">Feita</option>
                                                    @elseif($st->status=="paga")
                                                    <option value="{{$st->status}}" style="color: green;">Paga</option>
                                                    @else
                                                    <option value="{{$st->status}}" style="color: red;">Cancelada</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <label for="status">Status</label>
                                        </div>
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
            <div class="table-responsive-xl">
                <table class="table table-striped table-ordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Id</th>
                            <th>Status</th>
                            <th>Cliente</th>
                            <th>Produtos</th>
                            <th>Total</th>
                            <th>Pagamento</th>
                            <th>Data & Hora</th>
                            <th>Detalhes</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vendas as $venda)
                        <tr>
                            <td>{{$venda->id}}</td>
                            <td>@if($venda->status=="nova") <b style="color:yellow;">Nova</b> @else @if($venda->status=="feita") <b style="color:blue;">Feita</b> @else @if($venda->status=="paga") <b style="color:green;">Paga</b> @else @if($venda->status=="cancelada") <b style="color:red;">Cancelada</b> @else Indefinida  @endif @endif  @endif  @endif</td>
                            <td>@if($venda->cliente!="") {{$venda->cliente->nome}} @endif</td>
                            <td>{{$venda->total_produtos}}</td>
                            <td>{{ 'R$ '.number_format(($venda->total_final + $venda->juros), 2, ',', '.')}}</td>
                            <td>@if($venda->pagamento_forma!="") {{$venda->pagamento_forma->descricao}} @endif</td>
                            <td>{{date("d/m/Y H:i", strtotime($venda->created_at))}}</td>
                            
                            <td>
                                <button class="badge rounded-pill bg-light" data-bs-toggle="modal" data-bs-target="#modalProdutos{{$venda->id}}">
                                    <i class="material-icons blue md-24">info</i>
                                </button>
                                <!-- Modal -->
                                <div class="modal fade" id="modalProdutos{{$venda->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Venda Nº {{$venda->id}} @if($venda->cliente!="") - {{$venda->cliente->nome}} @endif @if($venda->pagamento_forma!="") - {{$venda->pagamento_forma->descricao}} @endif </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table table-striped table-bordered table-hover">
                                                    <caption>Criada em {{date("d/m/Y H:i", strtotime($venda->created_at))}} por {{$venda->usuario_criou}}</caption>
                                                    @if($venda->status=="paga")
                                                    <caption>Paga em {{date("d/m/Y H:i", strtotime($venda->updated_at))}} por {{$venda->usuario_pagou}}</caption>
                                                    @endif
                                                    @if($venda->status=="cancelada")
                                                    <caption>Cancelada em {{date("d/m/Y H:i", strtotime($venda->updated_at))}} por {{$venda->usuario_cancelou}} - Motivo: {{$venda->motivo_cancelamento}}</caption>
                                                    @endif
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th>Produto</th>
                                                            <th>Preço</th>
                                                            <th>Quantidade</th>
                                                            <th>Desconto</th>
                                                            <th>Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($venda->venda_produtos as $venda_produto)
                                                        <tr>
                                                            <td>{{$venda_produto->produto->nome}} {{$venda_produto->produto->marca}} ({{$venda_produto->produto->embalagem}})</td>
                                                            <td>{{ 'R$ '.number_format($venda_produto->produto->preco_atual, 2, ',', '.')}}</td>
                                                            <td>{{$venda_produto->qtd}}</td>
                                                            <td>{{ 'R$ '.number_format($venda_produto->desconto, 2, ',', '.')}}</td>
                                                            <td>{{ 'R$ '.number_format(($venda_produto->produto->preco_atual * $venda_produto->qtd) - ($venda_produto->desconto * $venda_produto->qtd), 2, ',', '.')}}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot class="table-dark">
                                                        <tr>
                                                            <td colspan="1">Totais</td>
                                                            <td>{{ 'R$ '.number_format($venda->valor_total, 2, ',', '.')}}</td>
                                                            <td>{{$venda->total_produtos}}</td>
                                                            <td>{{ 'R$ '.number_format($venda->desconto_total, 2, ',', '.')}}</td>
                                                            <td>{{ 'R$ '.number_format($venda->total_final, 2, ',', '.')}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="4">Juros</td>
                                                            <td>{{ 'R$ '.number_format($venda->juros, 2, ',', '.')}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="4">Total Final</td>
                                                            <td>{{ 'R$ '.number_format(($venda->total_final + $venda->juros), 2, ',', '.')}}</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                                @if($venda->observacao!="")
                                                <div class="table-responsive">
                                                    <div class="text-nowrap">
                                                        Observação: {{$venda->observacao}}
                                                    </div>
                                                </div>
                                                @else
                                                <h6>Sem observação!</h6>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($venda->status=="feita")
                                <!-- Button trigger modal -->
                                <button type="button" class="badge bg-danger" data-bs-toggle="modal" data-bs-target="#modalCancelar{{$venda->id}}" data-toggle="tooltip" data-placement="right" title="Cancelar">
                                    <i class="material-icons black">cancel</i>
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="modalCancelar{{$venda->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Venda Nº {{$venda->id}} @if($venda->cliente!="") - {{$venda->cliente->nome}} @endif @if($venda->pagamento_forma!="") - {{$venda->pagamento_forma->descricao}} @endif</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <h6>Tem certeza que deseja cancelar essa venda? Não será possivel reverter esta ação.</h6>
                                                <form id="form-cancelar" class="row g-3" action="/vendas/cancelar/{{$venda->id}}" method="POST">
                                                    @csrf
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text title-info">Motivo do Cancelamento</span>
                                                        <textarea class="form-control" name="motivo" id="motivo" required></textarea>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
                                                <button type="submit" form="form-cancelar" class="btn btn-danger">Cancelar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                    <button type="button" class="badge rounded-pill bg-success" data-bs-toggle="modal" data-bs-target="#modalPagar{{$venda->id}}" data-toggle="tooltip" data-placement="right" title="Pagar" onclick="atualizaTotaisVendas({{$venda->id}});">
                                        <i class="material-icons white">paid</i>
                                    </button>
    
                                    <!-- Modal -->
                                    <div class="modal fade" id="modalPagar{{$venda->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Venda Nº {{$venda->id}} @if($venda->cliente!="") - {{$venda->cliente->nome}} @endif @if($venda->pagamento_forma!="") - {{$venda->pagamento_forma->descricao}} @endif</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <h6>Confirme a forma de pagamento!</h6>
                                                    <form>
                                                        @csrf
                                                        <div class="form-floating">
                                                            <select class="form-select" id="forma_pagamento" name="pagamento" title="{{$venda->id}}" required>
                                                                <option value="{{$venda->pagamento_forma->id}}">{{$venda->pagamento_forma->descricao}}</option>
                                                                @foreach ($pagamentos as $pagamento)
                                                                @if($pagamento->id==$venda->pagamento_forma->id)
                                                                @else
                                                                <option value="{{$pagamento->id}}">{{$pagamento->descricao}} @if($pagamento->juros==true) @if($pagamento->tipo_juros=="porc") ({{$pagamento->valor_juros}} %) @else (R$ {{$pagamento->valor_juros}}) @endif @endif</option>
                                                                @endif
                                                                @endforeach
                                                            </select>
                                                            <label for="pagamento">Forma Pagamento (obrigatório)</label>
                                                        </div>
                                                    </form>
                                                    <hr/>
                                                    <div class="row">
                                                        <div class="col"></div>
                                                        <div class="col" style="text-align: right;">
                                                            <table class="table table-striped table-bordered table-hover">
                                                                <tbody>
                                                                    <tr class="h5">
                                                                        <td>Total</td>
                                                                        <td id="valorTotalFinal{{$venda->id}}"><b>R$ 0</b></td>
                                                                    </tr>
                                                                    <tr class="h4">
                                                                        <td>Juros</td>
                                                                        <td id="jurosFinal{{$venda->id}}"><b>R$ 0</b></td>
                                                                    </tr>
                                                                    <tr class="h3">
                                                                        <td>Total Final</td>
                                                                        <td id="valorFinal{{$venda->id}}"><b>R$ 0</b></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <h6>Tem certeza que deseja marcar como paga essa venda? Não será possivel reverter esta ação.</h6>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
                                                    <a type="button" href="/vendas/pagar/{{$venda->id}}" class="btn btn-success">Sim</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-footer">
                    {{ $vendas->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <a type="button" class="float-button" href="/vendas/nova" data-toggle="tooltip" data-placement="bottom" title="Nova Venda">
        <i class="material-icons blue md-60">add_circle</i>
    </a>
@endsection