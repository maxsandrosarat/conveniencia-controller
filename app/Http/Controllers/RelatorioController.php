<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\CompraProduto;
use App\Models\ListaCompra;
use Illuminate\Http\Request;
use App\Models\ProdutoEntrada;
use App\Models\Produto;
use App\Models\ProdutoExtra;
use App\Models\ProdutoSaida;
use App\Models\Venda;
use App\Models\VendaProduto;
use Illuminate\Support\Facades\Auth;
use PDF;

class RelatorioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index()
    {
        return view('relatorios.home_relatorios');
    }

    public function estoque()
    {
        return view('relatorios.home_relatorios_estoque');
    }

    public function entradas()
    {
        $prods = Produto::where('ativo',true)->orderBy('nome')->get();
        $rels = ProdutoEntrada::orderBy('created_at','desc')->orderBy('id','desc')->paginate(20);
        $view = "inicial";
        return view('relatorios.relatorio_entradas', compact('view','prods','rels'));
    }

    public function saidas()
    {
        $prods = Produto::where('ativo',true)->orderBy('nome')->get();
        $rels = ProdutoSaida::orderBy('created_at','desc')->orderBy('id','desc')->paginate(20);
        $view = "inicial";
        return view('relatorios.relatorio_saidas', compact('view','prods','rels'));
    }

    public function entradasFiltro(Request $request)
    {
        $codProd = $request->input('produto');
        if($request->input('dataInicio')!=""){
            $dataInicio = $request->input('dataInicio').' '."00:00:00";
        }
        if($request->input('dataFim')!=""){
            $dataFim = $request->input('dataFim').' '."23:59:00";
        }
            if(isset($codProd)){
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = ProdutoEntrada::where('produto_id',"$codProd")->whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = ProdutoEntrada::where('produto_id',"$codProd")->where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = ProdutoEntrada::where('produto_id',"$codProd")->where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = ProdutoEntrada::where('produto_id',"$codProd")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                }
            } else {
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = ProdutoEntrada::whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = ProdutoEntrada::where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = ProdutoEntrada::where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        return redirect('/relatorios/estoque/entradas');
                    }
                }
            }
        $prods = Produto::where('ativo',true)->orderBy('nome')->get();
        $view = "filtro";
        return view('relatorios.relatorio_entradas', compact('view','prods','rels'));
    }

    public function saidasFiltro(Request $request)
    {
        $codProd = $request->input('produto');
        if($request->input('dataInicio')!=""){
            $dataInicio = $request->input('dataInicio').' '."00:00:00";
        }
        if($request->input('dataFim')!=""){
            $dataFim = $request->input('dataFim').' '."23:59:00";
        }
            if(isset($codProd)){
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = ProdutoSaida::where('produto_id',"$codProd")->whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = ProdutoSaida::where('produto_id',"$codProd")->where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = ProdutoSaida::where('produto_id',"$codProd")->where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = ProdutoSaida::where('produto_id',"$codProd")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                }
            } else {
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = ProdutoSaida::whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = ProdutoSaida::where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = ProdutoSaida::where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        return redirect('/relatorios/estoque/saidas');
                    }
                }
            }
        $prods = Produto::where('ativo',true)->orderBy('nome')->get();
        $view = "filtro";
        return view('relatorios.relatorio_saidas', compact('view','prods','rels'));
    }

    public function indexVendas()
    {
        return view('relatorios.home_relatorios_vendas');
    }

    public function vendasProdutos()
    {
        $prods = Produto::where('ativo',true)->orderBy('nome')->get();
        $rels = VendaProduto::orderBy('created_at','desc')->orderBy('id','desc')->paginate(10);
        $view = "inicial";
        return view('relatorios.relatorio_vendas_produtos', compact('view','prods','rels'));
    }

    public function vendasProdutosFiltro(Request $request)
    {
        $status = $request->input('status');
        $codProd = $request->input('produto');
        if($request->input('dataInicio')!=""){
            $dataInicio = $request->input('dataInicio').' '."00:00:00";
        }
        if($request->input('dataFim')!=""){
            $dataFim = $request->input('dataFim').' '."23:59:00";
        }
        if(isset($status)){
            if(isset($codProd)){
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = VendaProduto::where('status','like',"%$status%")->where('produto_id',"$codProd")->whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = VendaProduto::where('status','like',"%$status%")->where('produto_id',"$codProd")->where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = VendaProduto::where('status','like',"%$status%")->where('produto_id',"$codProd")->where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = VendaProduto::where('status','like',"%$status%")->where('produto_id',"$codProd")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                }
            } else {
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = VendaProduto::where('status','like',"%$status%")->whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = VendaProduto::where('status','like',"%$status%")->where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = VendaProduto::where('status','like',"%$status%")->where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = VendaProduto::where('status','like',"%$status%")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                }
            }
        } else {
            if(isset($codProd)){
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = VendaProduto::where('produto_id',"$codProd")->whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = VendaProduto::where('produto_id',"$codProd")->where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = VendaProduto::where('produto_id',"$codProd")->where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = VendaProduto::where('produto_id',"$codProd")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                }
            } else {
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = VendaProduto::whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = VendaProduto::where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = VendaProduto::where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        return redirect('/relatorios/vendas/produtos');
                    }
                }
            }
        }
        $total_valor = $rels->sum('valor');
        $total_desconto = $rels->sum('desconto');
        $total_geral = $total_valor - $total_desconto;
        $prods = Produto::where('ativo',true)->orderBy('nome')->get();
        $view = "filtro";
        return view('relatorios.relatorio_vendas_produtos', compact('view','prods','rels','total_valor','total_desconto','total_geral'));
    }

    public function vendasClientes()
    {
        $clientes = Cliente::orderBy('nome')->get();
        $rels = Venda::orderBy('created_at','desc')->paginate(10);
        $view = "inicial";
        return view('relatorios.relatorio_vendas_clientes', compact('view','clientes','rels'));
    }

    public function vendasClientesFiltro(Request $request)
    {
        $status = $request->input('status');
        $codCliente = $request->input('cliente');
        if($request->input('dataInicio')!=""){
            $dataInicio = $request->input('dataInicio').' '."00:00:00";
        }
        if($request->input('dataFim')!=""){
            $dataFim = $request->input('dataFim').' '."23:59:00";
        }
        if(isset($status)){
            if(isset($codCliente)){
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = Venda::where('status','like',"%$status%")->where('cliente_id',"$codCliente")->whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = Venda::where('status','like',"%$status%")->where('cliente_id',"$codCliente")->where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = Venda::where('status','like',"%$status%")->where('cliente_id',"$codCliente")->where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = Venda::where('status','like',"%$status%")->where('cliente_id',"$codCliente")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                }
            } else {
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = Venda::where('status','like',"%$status%")->whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = Venda::where('status','like',"%$status%")->where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = Venda::where('status','like',"%$status%")->where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = Venda::where('status','like',"%$status%")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                }
            }
        } else {
            if(isset($codCliente)){
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = Venda::where('cliente_id',"$codCliente")->whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = Venda::where('cliente_id',"$codCliente")->where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = Venda::where('cliente_id',"$codCliente")->where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = Venda::where('cliente_id',"$codCliente")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                }
            } else {
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = Venda::whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = Venda::where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = Venda::where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        return redirect('/relatorios/vendas/clientes');
                    }
                }
            }
        }
        $total_valor = $rels->sum('total');
        $clientes = Cliente::orderBy('nome')->get();
        $view = "filtro";
        return view('relatorios.relatorio_vendas_clientes', compact('view','clientes','rels','total_valor'));
    }

    public function vendasClientesProdutos()
    {
        $clientes = Cliente::orderBy('nome')->get();
        $prods = Produto::where('ativo',true)->orderBy('nome')->get();
        $rels = Venda::orderBy('created_at','desc')->paginate(10);
        $view = "inicial";
        return view('relatorios.relatorio_clientes_produtos', compact('view','clientes','prods','rels'));
    }

    public function vendasClientesProdutosFiltro(Request $request)
    {
        $status = $request->input('status');
        $codCliente = $request->input('cliente');
        $codProd = $request->input('produto');
        if($request->input('dataInicio')!=""){
            $dataInicio = $request->input('dataInicio').' '."00:00:00";
        }
        if($request->input('dataFim')!=""){
            $dataFim = $request->input('dataFim').' '."23:59:00";
        }
        if(isset($status)){
            if(isset($codCliente)){
                if(isset($codProd)){
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $rels = Venda::where('status','like',"%$status%")->where('cliente_id',"$codCliente")->where('produto_id',"$codProd")->whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        } else {
                            $rels = Venda::where('status','like',"%$status%")->where('cliente_id',"$codCliente")->where('produto_id',"$codProd")->where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $rels = Venda::where('status','like',"%$status%")->where('cliente_id',"$codCliente")->where('produto_id',"$codProd")->where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        } else {
                            $rels = Venda::where('status','like',"%$status%")->where('cliente_id',"$codCliente")->where('produto_id',"$codProd")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        }
                    }
                } else {
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $rels = Venda::where('status','like',"%$status%")->where('cliente_id',"$codCliente")->whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        } else {
                            $rels = Venda::where('status','like',"%$status%")->where('cliente_id',"$codCliente")->where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $rels = Venda::where('status','like',"%$status%")->where('cliente_id',"$codCliente")->where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        } else {
                            $rels = Venda::where('status','like',"%$status%")->where('cliente_id',"$codCliente")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        }
                    }
                }
            } else {
                if(isset($codProd)){
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $rels = Venda::where('status','like',"%$status%")->where('produto_id',"$codProd")->whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        } else {
                            $rels = Venda::where('status','like',"%$status%")->where('produto_id',"$codProd")->where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $rels = Venda::where('status','like',"%$status%")->where('produto_id',"$codProd")->where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        } else {
                            $rels = Venda::where('status','like',"%$status%")->where('produto_id',"$codProd")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        }
                    }
                } else {
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $rels = Venda::where('status','like',"%$status%")->whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        } else {
                            $rels = Venda::where('status','like',"%$status%")->where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $rels = Venda::where('status','like',"%$status%")->where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        } else {
                            $rels = Venda::where('status','like',"%$status%")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        }
                    }
                }
            }
        } else {
            if(isset($codCliente)){
                if(isset($codProd)){
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $rels = Venda::where('cliente_id',"$codCliente")->where('produto_id',"$codProd")->whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        } else {
                            $rels = Venda::where('cliente_id',"$codCliente")->where('produto_id',"$codProd")->where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $rels = Venda::where('cliente_id',"$codCliente")->where('produto_id',"$codProd")->where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        } else {
                            $rels = Venda::where('cliente_id',"$codCliente")->where('produto_id',"$codProd")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        }
                    }
                } else {
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $rels = Venda::where('cliente_id',"$codCliente")->whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        } else {
                            $rels = Venda::where('cliente_id',"$codCliente")->where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $rels = Venda::where('cliente_id',"$codCliente")->where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        } else {
                            $rels = Venda::where('cliente_id',"$codCliente")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        }
                    }
                }
            } else {
                if(isset($codProd)){
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $rels = Venda::where('produto_id',"$codProd")->whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        } else {
                            $rels = Venda::where('produto_id',"$codProd")->where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $rels = Venda::where('produto_id',"$codProd")->where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        } else {
                            $rels = Venda::where('produto_id',"$codProd")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        }
                    }
                } else {
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $rels = Venda::whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        } else {
                            $rels = Venda::where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $rels = Venda::where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        } else {
                            $rels = Venda::orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        }
                    }
                }
            }
        }
        $clientes = Cliente::orderBy('nome')->get();
        $prods = Produto::where('ativo',true)->orderBy('nome')->get();
        $view = "filtro";
        return view('relatorios.relatorio_clientes_produtos', compact('view','clientes','prods','rels'));
    }

    //LISTAS DE COMPRAS
    public function indexListaCompras(){
        $listaProds = ListaCompra::with('produtos')->orderBy('created_at','desc')->paginate(10);
        return view('relatorios.lista_compra', compact('listaProds'));
    }

    public function selecionarListaCompra(){
        $prods = Produto::where('ativo',true)->where('estoque','<=',3)->orderBy('nome')->get();
        return view('relatorios.lista_compra_selecionar', compact('prods'));
    }

    public function novaListaCompra(Request $request){
        $prods = $request->input('produtos');
        $prodExtras = $request->input('produtosExtras');
        if($prods=="" && $prodExtras==""){
            return redirect('/relatorios/listaCompras')->with('mensagem', 'Lista não criada, nenhum item foi selecionado!')->with('type', 'warning');
        } else {
            $user = Auth::user()->name;
            $dataAtual = date("Y/m/d");
            $lista = new ListaCompra();
            $lista->data = $dataAtual;
            $lista->usuario = $user;
            $lista->save();
            if($prods!=""){
                foreach($prods as $prod){
                    $cp = new CompraProduto();
                    $cp->lista_compra_id = $lista->id;
                    $cp->produto_id = $prod;
                    $produto = Produto::find($prod);
                    $cp->estoque = $produto->estoque;
                    $cp->save();
                }
            }
            if($prodExtras!=""){
                foreach($prodExtras as $prodEx){
                    $pe = new ProdutoExtra();
                    $pe->lista_compra_id = $lista->id;
                    $pe->nome = $prodEx;
                    $pe->save();
                }
            }
        }
        return redirect('/relatorios/listaCompras')->with('mensagem', 'Lista criada com Sucesso!')->with('type', 'success');
    }

    public function gerarPdfListaCompra($lista_id)
    {
        $lista = ListaCompra::find($lista_id);
        $produtos = CompraProduto::where('lista_compra_id',"$lista_id")->get();
        $produtoExtras = ProdutoExtra::where('lista_compra_id',"$lista_id")->get();
        $pdf = \PDF::loadView('relatorios.compras_pdf', compact('lista','produtos','produtoExtras'));
        return $pdf->setPaper('a4')->stream('ListaCompra'.date("d-m-Y", strtotime($lista->data)).'.pdf');
    }

    public function apagarListaCompra($id)
    {
        $ocorrencia = ListaCompra::find($id);
        if(isset($ocorrencia)){
            CompraProduto::where('lista_compra_id',"$id")->delete();
            $ocorrencia->delete();
        }
        return back()->with('mensagem', 'Lista excluída com Sucesso!')->with('type', 'success');
    }

    public function removerItem($lista_id, $produto_id)
    {
        CompraProduto::where('lista_compra_id',"$lista_id")->where('produto_id',"$produto_id")->delete();
        return back()->with('mensagem', 'Item removido com Sucesso!')->with('type', 'success');
    }

    public function removerItemExtra($lista_id, $produto)
    {
        ProdutoExtra::where('lista_compra_id',"$lista_id")->where('nome',"$produto")->delete();
        return back()->with('mensagem', 'Item removido com Sucesso!')->with('type', 'success');
    }
}
