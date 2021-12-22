<?php

namespace App\Http\Controllers;

use App\Models\Despesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DespesaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function despesas(){
        $data = date("Y-m-d");
        $mesAtual = date("m");
        $ultimoDiaMes = date("t", mktime(0,0,0,$mesAtual,'01',date("Y")));
        $totalDia = 0;
        $totalMes = 0;
        $totalAberto = 0;

        $despsDia = Despesa::where('vencimento',"$data")->get();
        foreach ($despsDia as $desp) {
            if($desp->parcelado==1){
                $totalDia += $desp->valorParcela;
            } else {
                $totalDia += $desp->valorTotal;
            }
        }

        $despsMes = Despesa::whereBetween('vencimento',[date("Y")."-"."$mesAtual"."-01", date("Y")."-"."$mesAtual"."-"."$ultimoDiaMes"])->get();
        foreach ($despsMes as $desp) {
            if($desp->parcelado==1){
                $totalMes += $desp->valorParcela;
            } else {
                $totalMes += $desp->valorTotal;
            }
        }

        $despsAberto = Despesa::where('pago',false)->get();
        foreach ($despsAberto as $desp) {
            if($desp->parcelado==1){
                $totalAberto += $desp->valorParcela;
            } else {
                $totalAberto += $desp->valorTotal;
            }
        }
        $despesas = [
            "despesaDia" => $totalDia,
            "despesaMes"  => $totalMes,
            "despesaAberto" => $totalAberto,
        ];
        return view('despesas.home_despesas',compact('despesas'));
    }

    public function indexDespesas()
    {
        $valorTotal = 0;
        $view = "inicial";
        $despesas = Despesa::orderBy('vencimento')->paginate(20);
        foreach ($despesas as $desp) {
            if($desp->parcelado==1){
                $valorTotal += $desp->valorParcela;
            } else {
                $valorTotal += $desp->valorTotal;
            }
        }
        return view('despesas.lancamentos',compact('view','valorTotal','despesas'));
    }

    public function indexDespesasDia()
    {
        $valorTotal = 0;
        $view = "filtro";
        $data = date("Y-m-d");
        $despesas = Despesa::where('vencimento',"$data")->orderBy('vencimento')->paginate(50);
        foreach ($despesas as $desp) {
            if($desp->parcelado==1){
                $valorTotal += $desp->valorParcela;
            } else {
                $valorTotal += $desp->valorTotal;
            }
        }
        return view('despesas.lancamentos',compact('view','valorTotal','despesas'));
    }

    public function indexDespesasMes()
    {
        $valorTotal = 0;
        $view = "filtro";
        $mesAtual = date("m");
        $ultimoDiaMes = date("t", mktime(0,0,0,$mesAtual,'01',date("Y")));
        $despesas = Despesa::whereBetween('vencimento',[date("Y")."-"."$mesAtual"."-01", date("Y")."-"."$mesAtual"."-"."$ultimoDiaMes"])->paginate(50);
        foreach ($despesas as $desp) {
            if($desp->parcelado==1){
                $valorTotal += $desp->valorParcela;
            } else {
                $valorTotal += $desp->valorTotal;
            }
        }
        return view('despesas.lancamentos',compact('view','valorTotal','despesas'));
    }

    public function cadastrarDespesa(Request $request)
    {
        if($request->input('parcelado') == 1){
            for($i=1; $i<=$request->input('qtdParcelas'); $i++){
                $desp = new Despesa();
                $desp->descricao = $request->input('descricao')." - ".$i."/".$request->input('qtdParcelas');
                $dias = ($i * 30) - 30;
                $data = $request->input('vencimento');
                $desp->vencimento = date('Y-m-d', strtotime($data. ' + '.$dias.' days'));
                $desp->valorTotal = $request->input('valorTotal');
                $desp->formaPagamento = $request->input('formaPagamento');
                $desp->observacao = $request->input('observacao');
                $desp->parcelado = $request->input('parcelado');
                $desp->qtdParcelas = $request->input('qtdParcelas');
                $desp->valorParcela = $request->input('valorParcela');
                $desp->usuario = Auth::user()->name;
                $desp->save();
            }
        } else {
            $desp = new Despesa();
            $desp->descricao = $request->input('descricao');
            $desp->vencimento = $request->input('vencimento');
            $desp->valorTotal = $request->input('valorTotal');
            $desp->formaPagamento = $request->input('formaPagamento');
            $desp->observacao = $request->input('observacao');
            $desp->parcelado = $request->input('parcelado');
            $desp->usuario = Auth::user()->name;
            $desp->save();
        }

        return back()->with('mensagem', 'Despesa Cadastrada com Sucesso!');
    }

    public function pagarDespesa(Request $request, $id)
    {
        $desp = Despesa::find($id);

        if(isset($desp)){
            $desp->pago = 1;
            $desp->pagamento = $request->input('pagamento');
            $desp->save();
        }

        if($request->input('saldo')==1){
            $lanc = new Lancamento();
            $lanc->tipo = "retirada";
            $valor = 0;
            if($desp->parcelado==1){
                $valor = $desp->valorParcela;
            } else {
                $valor = $desp->valorTotal;
            }
            $lanc->valor = $valor;
            $lanc->usuario = Auth::user()->name;
            $lanc->motivo = "Pagamento de Despesa";
            $lanc->save();
            $saldo = Saldo::find(1);
            $saldo->saldo -= $valor;
            $saldo->save();
        } else {

        }

        return back()->with('mensagem', 'Despesa Paga com Sucesso!');
    }

    public function editarDespesa(Request $request, $id)
    {
        $desp = Despesa::find($id);


        if(isset($desp)){
            $desp->descricao = $request->input('descricao');
            $desp->vencimento = $request->input('vencimento');
            $desp->formaPagamento = $request->input('formaPagamento');
            $desp->observacao = $request->input('observacao');
            $desp->save();
        }

        return back()->with('mensagem', 'Despesa Alterada com Sucesso!');
    }

    public function apagarDespesa($id)
    {
        $desp = Despesa::find($id);


        if(isset($desp)){
            $desp->delete();
        }

        return back()->with('mensagem', 'Despesa Excluída com Sucesso!');
    }

    public function filtroDespesa(Request $request)
    {
        $codigo = $request->input('codigo');
        $descricao = $request->input('descricao');
        $dataInicio = $request->input('dataInicio');
        $dataFim = $request->input('dataFim');
        if(isset($codigo)){
            if(isset($descricao)){
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $despesas = Despesa::where('id',"$codigo")->where('descricao','like',"%$descricao%")->whereBetween('vencimento',["$dataInicio", "$dataFim"])->orderBy('vencimento')->paginate(100);
                    } else {
                        $despesas = Despesa::where('id',"$codigo")->where('descricao','like',"%$descricao%")->whereBetween('vencimento',["$dataInicio", date("Y/m/d")])->orderBy('vencimento')->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $despesas = Despesa::where('id',"$codigo")->where('descricao','like',"%$descricao%")->whereBetween('vencimento',["", "$dataFim"])->orderBy('vencimento')->paginate(100);
                    } else {
                        $despesas = Despesa::where('id',"$codigo")->where('descricao','like',"%$descricao%")->orderBy('vencimento')->paginate(100);
                    }
                }
            } else {
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $despesas = Despesa::where('id',"$codigo")->whereBetween('vencimento',["$dataInicio", "$dataFim"])->orderBy('vencimento')->paginate(100);
                    } else {
                        $despesas = Despesa::where('id',"$codigo")->whereBetween('vencimento',["$dataInicio", date("Y/m/d")])->orderBy('vencimento')->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $despesas = Despesa::where('id',"$codigo")->whereBetween('vencimento',["", "$dataFim"])->orderBy('vencimento')->paginate(100);
                    } else {
                        $despesas = Despesa::where('id',"$codigo")->orderBy('vencimento')->paginate(100);
                    }
                }
            }
        } else {
            if(isset($descricao)){
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $despesas = Despesa::where('descricao','like',"%$descricao%")->whereBetween('vencimento',["$dataInicio", "$dataFim"])->orderBy('vencimento')->paginate(100);
                    } else {
                        $despesas = Despesa::where('descricao','like',"%$descricao%")->whereBetween('vencimento',["$dataInicio", date("Y/m/d")])->orderBy('vencimento')->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $despesas = Despesa::where('descricao','like',"%$descricao%")->whereBetween('vencimento',["", "$dataFim"])->orderBy('vencimento')->paginate(100);
                    } else {
                        $despesas = Despesa::where('descricao','like',"%$descricao%")->orderBy('vencimento')->paginate(100);
                    }
                }
            } else {
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $despesas = Despesa::whereBetween('vencimento',["$dataInicio", "$dataFim"])->orderBy('vencimento')->paginate(100);
                    } else {
                        $despesas = Despesa::whereBetween('vencimento',["$dataInicio", date("Y/m/d")])->orderBy('vencimento')->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $despesas = Despesa::whereBetween('vencimento',["", "$dataFim"])->orderBy('vencimento')->paginate(100);
                    } else {
                        return redirect('/despesas/lancamentos');
                    }
                }
            }
        }
        $valorTotal = 0;
        foreach ($despesas as $desp) {
            if($desp->parcelado==1){
                $valorTotal += $desp->valorParcela;
            } else {
                $valorTotal += $desp->valorTotal;
            }
        }
        $view = "filtro";
        return view('despesas.lancamentos',compact('view','valorTotal','despesas'));
    }
}
