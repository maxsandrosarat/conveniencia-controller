@extends('layouts.app', ["current"=>"vendas"])

@section('body')
@php
	$page = "Nova Venda";
@endphp
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card border">
            <div class="card-body">
                <a href="/vendas" class="btn btn-success" title="Voltar"><i class="material-icons white">reply</i></a>
                <br/><br/>
                <h3 class="card-title">Nova Venda - Nº: {{$venda->id}}</h3>
                @if(session('mensagem'))
                    <div class="alert @if(session('type')=="success") alert-success @else @if(session('type')=="warning") alert-warning @else @if(session('type')=="danger") alert-danger @else alert-info @endif @endif @endif alert-dismissible fade show" role="alert">
                        {{session('mensagem')}}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="input-group">
                    <form id="form-venda" class="row g-3" action="/vendas" method="POST">
                        @csrf
                        <input id="venda" type="hidden" name="venda" value="{{$venda->id}}">
                        <input type="hidden" name="pago">
                        <div class="form-floating">
                            <select class="form-select" id="cliente" name="cliente">
                                <option value="">Selecione</option>
                                @foreach ($clientes as $cliente)
                                <option value="{{$cliente->id}}">{{$cliente->nome}} @if($cliente->cpf!="") ({{$cliente->cpf}}) @endif</option>
                                @endforeach
                            </select>
                            <label for="venda">Cliente (opcional)</label>
                        </div>
                        <hr/>
                        <h5>Produtos</h5>
                        <div class="row">
                            <div class="col form-floating">
                                <input id="nomeProd" class="form-control" type="text" name="nomeProd" placeholder="Digite o Nome">
                                <label for="nomeProd">Digite o Nome</label>
                            </div>
                            <div class="col form-floating">
                                <input id="idProd" class="form-control" type="number" name="idProd" placeholder="Digite o ID">
                                <label for="idProd">Digite o ID</label>
                            </div>
                            <div class="col form-floating">
                                <input id="codBarProd" class="form-control" type="number" size="13" name="codBarProd" placeholder="Digite o Código de Barras">
                                <label for="codBarProd">Digite o Código de Barras</label>
                            </div>
                        </div>
                        <hr/>
                        <ul class="list-group" id="listaProdutos">
                                
                        </ul>
                        <hr/>
                        <table class="table table-striped table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th colspan="6" style="white-space: nowrap; text-align: center; vertical-align: middle;">Produtos Selecionados</th>
                                </tr>
                                <tr>
                                    <th><i class="material-icons green">check_box</i></th>
                                    <th>Produto</th>
                                    <th>Preço</th>
                                    <th>Quantidade</th>
                                    <th>Desconto</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody id="listaProdutosSelecionados">
                                
                            </tbody>
                            <tfoot class="table-dark">
                                <tr style="text-align: center;">
                                    <th colspan="2">Totais</th>
                                    <td id="valorTotal"><b>R$ 0</b></td>
                                    <td id="qtdTotal"><b>0</b></td>
                                    <td id="descontoTotal"><b>R$ 0</b></td>
                                    <td id="totalGeral"><b>R$ 0</b></td>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="form-floating">
                            <select class="form-select" id="pagamento" name="pagamento" required>
                                <option value="">Selecione</option>
                                @foreach ($pagamentos as $pagamento)
                                <option value="{{$pagamento->id}}">{{$pagamento->descricao}} @if($pagamento->juros==true) @if($pagamento->tipo_juros=="porc") ({{$pagamento->valor_juros}} %) @else (R$ {{$pagamento->valor_juros}}) @endif @endif</option>
                                @endforeach
                            </select>
                            <label for="pagamento">Forma Pagamento (obrigatório)</label>
                        </div>
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
                        <div class="input-group mb-3">
                            <span class="input-group-text title-info">Observação</span>
                            <textarea class="form-control" name="observacao" id="observacao"></textarea>
                        </div>
                    </form>
                </div> 
            </div>
            <div class="card-footer d-grid gap-2 d-md-fle justify-content-md-end">
                <button id="btn-concluir" class="btn btn-outline-primary btn-sn disabled" data-bs-toggle="modal" data-bs-target="#modalPagamento">Concluir</button>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="modalPagamento" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Pagamento</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h5>Essa venda já foi paga?</h5>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-warning" onclick="concluirVenda(0);">Ainda Não</button>
                            <button type="button" class="btn btn-success" onclick="concluirVenda(1);">Sim, Paga!</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection