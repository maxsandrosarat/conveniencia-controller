<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\PagamentoForma;
use App\Models\Produto;
use App\Models\ProdutoEntrada;
use App\Models\ProdutoSaida;
use App\Models\Venda;
use App\Models\VendaProduto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VendaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    //PRODUTOS
    public function buscarProduto(Request $request)
    {
        $query = Produto::query();
        if(isset($request->nome)){
            $query->where('nome', 'LIKE', '%' . $request->nome . '%');
        }
        if(isset($request->id)){
            $query->where('id', $request->id);
        }
        if(isset($request->cod)){
            $query->where('codigo_barras', 'LIKE', '%' . $request->cod . '%');
        }
        $produtos = $query->where('ativo', true)->where('estoque','>=',1)->orderBy('estoque')->orderBy('nome')->get();
        
        return $produtos;
    }

    public function selecionarProduto(Request $request)
    {
        $validador = VendaProduto::where('venda_id',"$request->venda")->where('produto_id',"$request->produto")->count();
        if($validador==0){
            $produto = Produto::find($request->produto);
            $vendaProd = new VendaProduto();
            $vendaProd->venda_id = $request->venda;
            $vendaProd->produto_id = $request->produto;
            $vendaProd->valor = $produto->preco_atual;
            $vendaProd->qtd += 1;
            $vendaProd->save();
            $venda = Venda::find($request->venda);
            $venda->valor_total += $produto->preco_atual;
            $venda->total_final += $produto->preco_atual;
            $venda->total_produtos += 1;
            if($venda->pagamento_forma_id!="" || $venda->pagamento_forma_id!=null){
                $pagamento = PagamentoForma::find($venda->pagamento_forma_id);
                if($pagamento->juros){
                    if($pagamento->tipo_juros=="porc"){
                        $juros = ($venda->total_final * $pagamento->valor_juros)/100;
                        $venda->juros = $juros;
                        $venda->save();
                    } else {
                        $venda->juros = $pagamento->valor_juros;
                        $venda->save();
                    }
                } else {
                    $venda->juros = 0;
                    $venda->save();
                }
            } else {
                $venda->save();
            }
            return $produto;
        } else {
            return 0;
        }
    }

    public function qtdProdutoVenda(Request $request)
    {
        $vdPd = VendaProduto::where('venda_id',"$request->venda")->where('produto_id',"$request->produto")->with('produto')->first();
        return $vdPd;
    }

    public function adicionarProduto(Request $request)
    {
        $venda = Venda::find($request->venda);
        $vdPd = VendaProduto::where('venda_id',"$request->venda")->where('produto_id',"$request->produto")->first();
        if($vdPd->desconto>0){
            $venda->desconto_total -= ($vdPd->desconto * $vdPd->qtd);
            $venda->desconto_total += ($vdPd->desconto * ($vdPd->qtd + 1));
        }
        $vdPd->qtd += 1;
        $vdPd->save();
        $venda->valor_total += $vdPd->valor;
        $venda->total_produtos += 1;
        $venda->total_final = $venda->valor_total - $venda->desconto_total;
        if($venda->pagamento_forma_id!="" || $venda->pagamento_forma_id!=null){
            $pagamento = PagamentoForma::find($venda->pagamento_forma_id);
            if($pagamento->juros){
                if($pagamento->tipo_juros=="porc"){
                    $juros = ($venda->total_final * $pagamento->valor_juros)/100;
                    $venda->juros = $juros;
                    $venda->save();
                } else {
                    $venda->juros = $pagamento->valor_juros;
                    $venda->save();
                }
            } else {
                $venda->juros = 0;
                $venda->save();
            }
        } else {
            $venda->save();
        }
        $produto = Produto::find($request->produto);
        if($produto->estoque<$vdPd->qtd){
            return 0;
        } else {
            return $vdPd->qtd;
        }
    }

    public function removerProduto(Request $request)
    {
        if($request->qtd==0){
            $vp = VendaProduto::where('venda_id',"$request->venda")->where('produto_id',"$request->produto")->first();
            $venda = Venda::find($request->venda);
            $venda->valor_total -= $vp->valor * $vp->qtd;
            $venda->desconto_total -= $vp->desconto * $vp->qtd;
            $venda->total_produtos -= $vp->qtd;
            $venda->total_final -= ($vp->valor * $vp->qtd) - ($vp->desconto * $vp->qtd);
            if($venda->pagamento_forma_id!="" || $venda->pagamento_forma_id!=null){
                $pagamento = PagamentoForma::find($venda->pagamento_forma_id);
                if($pagamento->juros){
                    if($pagamento->tipo_juros=="porc"){
                        $juros = ($venda->total_final * $pagamento->valor_juros)/100;
                        $venda->juros = $juros;
                        $venda->save();
                    } else {
                        $venda->juros = $pagamento->valor_juros;
                        $venda->save();
                    }
                } else {
                    $venda->juros = 0;
                    $venda->save();
                }
            } else {
                $venda->save();
            }
            $vp->delete();
            return 0;
        } else {
            $venda = Venda::find($request->venda);
            $vdPd = VendaProduto::where('venda_id',"$request->venda")->where('produto_id',"$request->produto")->first();
            if($vdPd->qtd<=1){
                if($vdPd->desconto>0){
                    $venda->desconto_total -= ($vdPd->desconto * 1);
                }
                $vdPd->qtd -= 1;
                $vdPd->save();
                $venda->valor_total -= $vdPd->valor;
                $venda->total_produtos -= 1;
                $venda->total_final = $venda->valor_total - $venda->desconto_total;
                if($venda->pagamento_forma_id!="" || $venda->pagamento_forma_id!=null){
                    $pagamento = PagamentoForma::find($venda->pagamento_forma_id);
                    if($pagamento->juros){
                        if($pagamento->tipo_juros=="porc"){
                            $juros = ($venda->total_final * $pagamento->valor_juros)/100;
                            $venda->juros = $juros;
                            $venda->save();
                        } else {
                            $venda->juros = $pagamento->valor_juros;
                            $venda->save();
                        }
                    } else {
                        $venda->juros = 0;
                        $venda->save();
                    }
                } else {
                    $venda->save();
                }
                VendaProduto::where('venda_id',"$request->venda")->where('produto_id',"$request->produto")->delete();
                return 0;
            } else{
                if($vdPd->desconto>0){
                    $venda->desconto_total -= ($vdPd->desconto * 1);
                }
                $vdPd->qtd -= 1;
                $vdPd->save();
                $venda->valor_total -= $vdPd->valor;
                $venda->total_produtos -= 1;
                $venda->total_final = $venda->valor_total - $venda->desconto_total;
                if($venda->pagamento_forma_id!="" || $venda->pagamento_forma_id!=null){
                    $pagamento = PagamentoForma::find($venda->pagamento_forma_id);
                    if($pagamento->juros){
                        if($pagamento->tipo_juros=="porc"){
                            $juros = ($venda->total_final * $pagamento->valor_juros)/100;
                            $venda->juros = $juros;
                            $venda->save();
                        } else {
                            $venda->juros = $pagamento->valor_juros;
                            $venda->save();
                        }
                    } else {
                        $venda->juros = 0;
                        $venda->save();
                    }
                } else {
                    $venda->save();
                }
                return $vdPd->qtd;
            }
        }
        
    }

    public function descontoProduto(Request $request)
    {
        $venda = Venda::find($request->venda);
        $vdPd = VendaProduto::where('venda_id',"$request->venda")->where('produto_id',"$request->produto")->first();
        if($venda->desconto_total>=$vdPd->desconto){
            $venda->desconto_total -= ($vdPd->desconto * $vdPd->qtd);
        }
        $vdPd->desconto = str_replace(',', '.', $request->valor);
        $vdPd->save();
        $venda->desconto_total += (str_replace(',', '.', $request->valor) * $vdPd->qtd);
        $venda->total_final = $venda->valor_total - $venda->desconto_total;
        if($venda->pagamento_forma_id!="" || $venda->pagamento_forma_id!=null){
            $pagamento = PagamentoForma::find($venda->pagamento_forma_id);
            if($pagamento->juros){
                if($pagamento->tipo_juros=="porc"){
                    $juros = ($venda->total_final * $pagamento->valor_juros)/100;
                    $venda->juros = $juros;
                    $venda->save();
                } else {
                    $venda->juros = $pagamento->valor_juros;
                    $venda->save();
                }
            } else {
                $venda->juros = 0;
                $venda->save();
            }
        } else {
            $venda->save();
        }
        return $vdPd->desconto;
    }

    //VENDAS
    public function indexVendas()
    {
        $vendasVerificar = Venda::where('status','nova')->get();
        $contador = Venda::count();
        foreach ($vendasVerificar as $venda){
            if($venda->id <= $contador){
                $vd = Venda::find($venda->id);
                $vd->status = "cancelada";
                $vd->usuario_cancelou = "Sistema";
                $vd->motivo_cancelamento = "Não finalizada";
                $vd->save();
                $vps = VendaProduto::where('venda_id', "$venda->id")->get();
                foreach ($vps as $vp){
                    $vendaP = VendaProduto::find($vp->id);
                    $vendaP->status = "cancelado";
                    $vendaP->save();
                }
            }
        }
        $clientes = Cliente::orderBy('nome')->get();
        $status = DB::table('vendas')->select(DB::raw("status"))->groupBy('status')->get();
        $pagamentos = PagamentoForma::orderBy('descricao')->get();
        $vendas = Venda::where('status','<>','cancelada')->orderBy('created_at','desc')->paginate(20);
        $view = "inicial";
        return view('vendas.vendas',compact('view','clientes','status','pagamentos','vendas'));
    }

    public function novaVenda()
    {
        $venda = new Venda();
        $venda->usuario_criou = Auth::user()->name;
        $venda->save();
        $clientes = Cliente::where('ativo',true)->orderBy('nome')->get();
        $pagamentos = PagamentoForma::where('ativo',true)->get();
        return view('vendas.nova_venda',compact('venda','clientes','pagamentos'));
    }

    public function filtroVendas(Request $request)
    {
        $query = Venda::query();
        if(isset($request->dataInicio)){
            if(isset($request->dataFim)){
                $query->whereBetween('created_at',["$request->dataInicio"." 00:00", "$request->dataFim"." 23:59"])->paginate(100);
            } else {
                $query->whereBetween('created_at',["$request->dataInicio"." 00:00", date("Y/m/d H:i")])->paginate(100);
            }
        } else {
            if(isset($request->dataFim)){
                $query->whereBetween('created_at',["", "$request->dataFim"." 23:59"])->paginate(100);
            }
        }
        if(isset($request->cliente)){
            $query->where('cliente_id', $request->cliente);
        }
        if(isset($request->pagamento)){
            $query->where('pagamento_forma_id', $request->pagamento);
        }
        if(isset($request->status)){
            $query->where('status', $request->status);
        }
        $vendas = $query->orderBy('created_at','desc')->paginate(100);
        $view = "filtro";
        $clientes = Cliente::orderBy('nome')->get();
        $status = DB::table('vendas')->select(DB::raw("status"))->groupBy('status')->get();
        $pagamentos = PagamentoForma::orderBy('descricao')->get();
        return view('vendas.vendas', compact('view','clientes','status','pagamentos','vendas'));
    }

    public function cadastrarVenda(Request $request)
    {
        $venda = Venda::find($request->venda);
        if(isset($request->observacao)){
            $venda->observacao = $request->observacao;
        }
        if($request->pago){
            $venda->status = "paga";
            $venda->usuario_pagou = Auth::user()->name;
            $vps = VendaProduto::where('venda_id',"$request->venda")->get();
            foreach ($vps as $vp){
                $vendaP = VendaProduto::find($vp->id);
                $vendaP->status = "pago";
                $vendaP->save();
                $produto = Produto::find($vp->produto_id);
                $produto->estoque -= $vendaP->qtd;
                $produto->save();
                $prodEnt = ProdutoEntrada::where('produto_id',"$vp->produto_id")->where('finalizado', false)->first();
                $disponivel = $prodEnt->quantidade_entrada - $prodEnt->quantidade_saida;
                if($disponivel>=$vendaP->qtd){
                    $prodEnt->quantidade_saida+=$vendaP->qtd;
                    $prodEnt->save();
                    $prodSaida = new ProdutoSaida();
                    $prodSaida->produto_id = $produto->id;
                    $prodSaida->quantidade_saida = $vendaP->qtd;
                    $prodSaida->custo = $prodEnt->custo;
                    $prodSaida->preco = $produto->preco_atual;
                    $prodSaida->desconto = $vendaP->desconto;
                    $prodSaida->lucro = (($prodSaida->preco - $prodSaida->custo) - $prodSaida->desconto) * $prodSaida->quantidade_saida;
                    $prodSaida->usuario = Auth::user()->name;
                    $prodSaida->motivo = "venda";
                    $prodSaida->save();
                    if($prodEnt->quantidade_entrada==$prodEnt->quantidade_saida){
                        $prodEnt->finalizado = true;
                        $prodEnt->save();
                    }
                } else {
                    $resto = $vendaP->qtd - $disponivel;
                    $prodEnt->quantidade_saida+=$disponivel;
                    $prodEnt->save();
                    $prodSaida = new ProdutoSaida();
                    $prodSaida->produto_id = $produto->id;
                    $prodSaida->quantidade_saida = $disponivel;
                    $prodSaida->custo = $prodEnt->custo;
                    $prodSaida->preco = $produto->preco_atual;
                    $prodSaida->desconto = $vendaP->desconto;
                    $prodSaida->lucro = (($prodSaida->preco - $prodSaida->custo) - $prodSaida->desconto) * $prodSaida->quantidade_saida;
                    $prodSaida->usuario = Auth::user()->name;
                    $prodSaida->motivo = "venda";
                    $prodSaida->save();
                    if($prodEnt->quantidade_entrada==$prodEnt->quantidade_saida){
                        $prodEnt->finalizado = true;
                        $prodEnt->save();
                    }
                    do{
                        $disponivel = 0;
                        $outroProdEnt = "";
                        $outroProdEnt = ProdutoEntrada::where('produto_id',"$vp->produto_id")->where('finalizado', false)->first();
                        $disponivel = $outroProdEnt->quantidade_entrada - $outroProdEnt->quantidade_saida;
                        
                        if($resto>$disponivel){
                            $outroProdEnt->quantidade_saida+=$disponivel;
                            $outroProdEnt->save();
                            $prodSaida = new ProdutoSaida();
                            $prodSaida->produto_id = $produto->id;
                            $prodSaida->quantidade_saida = $disponivel;
                            $prodSaida->custo = $outroProdEnt->custo;
                            $prodSaida->preco = $produto->preco_atual;
                            $prodSaida->desconto = $vendaP->desconto;
                            $prodSaida->lucro = (($prodSaida->preco - $prodSaida->custo) - $prodSaida->desconto) * $prodSaida->quantidade_saida;
                            $prodSaida->usuario = Auth::user()->name;
                            $prodSaida->motivo = "venda";
                            $prodSaida->save();
                        } else{
                            $outroProdEnt->quantidade_saida+=$resto;
                            $outroProdEnt->save();
                            $prodSaida = new ProdutoSaida();
                            $prodSaida->produto_id = $produto->id;
                            $prodSaida->quantidade_saida = $resto;
                            $prodSaida->custo = $outroProdEnt->custo;
                            $prodSaida->preco = $produto->preco_atual;
                            $prodSaida->desconto = $vendaP->desconto;
                            $prodSaida->lucro = (($prodSaida->preco - $prodSaida->custo) - $prodSaida->desconto) * $prodSaida->quantidade_saida;
                            $prodSaida->usuario = Auth::user()->name;
                            $prodSaida->motivo = "venda";
                            $prodSaida->save();
                        }
                        if($outroProdEnt->quantidade_entrada==$outroProdEnt->quantidade_saida){
                            $outroProdEnt->finalizado = true;
                            $outroProdEnt->save();
                        }
                        $resto = $resto - $disponivel;
                    } while ($resto >= 1);
                }
            }
        } else {
            $venda->status = "feita";
            $vps = VendaProduto::where('venda_id',"$request->venda")->get();
            foreach ($vps as $vp){
                $vendaP = VendaProduto::find($vp->id);
                $produto = Produto::find($vp->produto_id);
                $produto->estoque -= $vendaP->qtd;
                $produto->save();
            }
        }
        $venda->save();
        return redirect('/vendas')->with('mensagem', 'Venda finalizada com Sucesso!')->with('type', 'success');
    }

    public function cancelarVenda($id, Request $request)
    {
        $venda = Venda::find($id);   
        if(isset($venda)){
            $venda->status = "cancelada";
            $venda->usuario_cancelou = Auth::user()->name;
            $venda->motivo_cancelamento = $request->motivo;
            $venda->save();
            $vps = VendaProduto::where('venda_id', "$id")->get();
            foreach ($vps as $vp){
                $vendaP = VendaProduto::find($vp->id);
                $vendaP->status = "cancelado";
                $vendaP->save();
                $produto = Produto::find($vp->produto_id);
                $produto->estoque += $vendaP->qtd;
                $produto->save();
            }
            return back()->with('mensagem', 'Venda cancelada com Sucesso!')->with('type', 'success');
        } else {
            return back()->with('mensagem', 'Essa Venda não existe!')->with('type', 'warning');
        }
    }

    public function pagarVenda($id)
    {
        $venda = Venda::find($id);   
        if(isset($venda)){
            $venda->status = "paga";
            $venda->usuario_pagou = Auth::user()->name;
            $venda->save();
            $vps = VendaProduto::where('venda_id', "$id")->get();
            foreach ($vps as $vp){
                $vendaP = VendaProduto::find($vp->id);
                $vendaP->status = "pago";
                $vendaP->save();
                $produto = Produto::find($vp->produto_id);
                $prodEnt = ProdutoEntrada::where('produto_id',"$vp->produto_id")->where('finalizado', false)->first();
                $disponivel = $prodEnt->quantidade_entrada - $prodEnt->quantidade_saida;
                if($disponivel>=$vendaP->qtd){
                    $prodEnt->quantidade_saida+=$vendaP->qtd;
                    $prodEnt->save();
                    $prodSaida = new ProdutoSaida();
                    $prodSaida->produto_id = $produto->id;
                    $prodSaida->quantidade_saida = $vendaP->qtd;
                    $prodSaida->custo = $prodEnt->custo;
                    $prodSaida->preco = $produto->preco_atual;
                    $prodSaida->desconto = $vendaP->desconto;
                    $prodSaida->lucro = (($prodSaida->preco - $prodSaida->custo) - $prodSaida->desconto) * $prodSaida->quantidade_saida;
                    $prodSaida->usuario = Auth::user()->name;
                    $prodSaida->motivo = "venda";
                    $prodSaida->save();
                    if($prodEnt->quantidade_entrada==$prodEnt->quantidade_saida){
                        $prodEnt->finalizado = true;
                        $prodEnt->save();
                    }
                } else {
                    $resto = $vendaP->qtd - $disponivel;
                    $prodEnt->quantidade_saida+=$disponivel;
                    $prodEnt->save();
                    $prodSaida = new ProdutoSaida();
                    $prodSaida->produto_id = $produto->id;
                    $prodSaida->quantidade_saida = $disponivel;
                    $prodSaida->custo = $prodEnt->custo;
                    $prodSaida->preco = $produto->preco_atual;
                    $prodSaida->desconto = $vendaP->desconto;
                    $prodSaida->lucro = (($prodSaida->preco - $prodSaida->custo) - $prodSaida->desconto) * $prodSaida->quantidade_saida;
                    $prodSaida->usuario = Auth::user()->name;
                    $prodSaida->motivo = "venda";
                    $prodSaida->save();
                    if($prodEnt->quantidade_entrada==$prodEnt->quantidade_saida){
                        $prodEnt->finalizado = true;
                        $prodEnt->save();
                    }
                    do{
                        $disponivel = 0;
                        $outroProdEnt = "";
                        $outroProdEnt = ProdutoEntrada::where('produto_id',"$vp->produto_id")->where('finalizado', false)->first();
                        $disponivel = $outroProdEnt->quantidade_entrada - $outroProdEnt->quantidade_saida;
                        
                        if($resto>$disponivel){
                            $outroProdEnt->quantidade_saida+=$disponivel;
                            $outroProdEnt->save();
                            $prodSaida = new ProdutoSaida();
                            $prodSaida->produto_id = $produto->id;
                            $prodSaida->quantidade_saida = $disponivel;
                            $prodSaida->custo = $outroProdEnt->custo;
                            $prodSaida->preco = $produto->preco_atual;
                            $prodSaida->desconto = $vendaP->desconto;
                            $prodSaida->lucro = (($prodSaida->preco - $prodSaida->custo) - $prodSaida->desconto) * $prodSaida->quantidade_saida;
                            $prodSaida->usuario = Auth::user()->name;
                            $prodSaida->motivo = "venda";
                            $prodSaida->save();
                        } else{
                            $outroProdEnt->quantidade_saida+=$resto;
                            $outroProdEnt->save();
                            $prodSaida = new ProdutoSaida();
                            $prodSaida->produto_id = $produto->id;
                            $prodSaida->quantidade_saida = $resto;
                            $prodSaida->custo = $outroProdEnt->custo;
                            $prodSaida->preco = $produto->preco_atual;
                            $prodSaida->desconto = $vendaP->desconto;
                            $prodSaida->lucro = (($prodSaida->preco - $prodSaida->custo) - $prodSaida->desconto) * $prodSaida->quantidade_saida;
                            $prodSaida->usuario = Auth::user()->name;
                            $prodSaida->motivo = "venda";
                            $prodSaida->save();
                        }
                        if($outroProdEnt->quantidade_entrada==$outroProdEnt->quantidade_saida){
                            $outroProdEnt->finalizado = true;
                            $outroProdEnt->save();
                        }
                        $resto = $resto - $disponivel;
                        var_dump($resto);
                    } while ($resto >= 1);
                }
            }
            return back()->with('mensagem', 'Venda paga com Sucesso!')->with('type', 'success');
        } else {
            return back()->with('mensagem', 'Essa Venda não existe!')->with('type', 'warning');
        }
    }

    public function buscarVenda($id)
    {
        return Venda::find($id);
    }

    public function cliente(Request $request)
    {
        $venda = Venda::find($request->venda);
        $venda->cliente_id = $request->cliente;
        $venda->save();
        return Cliente::find($request->cliente);
    }

    public function pagamento(Request $request)
    {
        if(isset($request->pagamento)){
            $venda = Venda::find($request->venda);
            $venda->pagamento_forma_id = $request->pagamento;
            $venda->save();
            $pagamento = PagamentoForma::find($request->pagamento);
            if($pagamento->juros){
                if($pagamento->tipo_juros=="porc"){
                    $juros = ($venda->total_final * $pagamento->valor_juros)/100;
                    $venda->juros = $juros;
                    $venda->save();
                } else {
                    $venda->juros = $pagamento->valor_juros;
                    $venda->save();
                }
            } else {
                $venda->juros = 0;
                $venda->save();
            }
            return $pagamento;
        } else {
            return 0;
        }
        
    }
}