<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\PagamentoForma;
use App\Models\Produto;
use App\Models\ProdutoEntrada;
use App\Models\ProdutoPreco;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CadastroController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    //CADASTROS
    public function indexCadastros()
    {
        return view('cadastros.home_cadastros');
    }

    //CATEGORIA
    public function indexCategorias()
    {
        $categorias = Categoria::all();
        return view('cadastros.categorias',compact('categorias'));
    }

    public function novaCategoria(Request $request)
    {
        $categoria = new Categoria();
        $categoria->nome = $request->nome;
        $categoria->save();
        return back()->with('mensagem', 'Categoria cadastrada com Sucesso!')->with('type', 'success');
    }

    public function editarCategoria(Request $request, $id)
    {
        $categoria = Categoria::find($id);
        if(isset($categoria)){
            $categoria->nome = $request->nome;
            $categoria->save();
            return back()->with('mensagem', 'Categoria alterada com Sucesso!')->with('type', 'success');
        } else{
            return back()->with('mensagem', 'Essa categoria não existe!')->with('type', 'warning');
        }
    }

    public function ativarCategoria($id)
    {
        $categoria = Categoria::find($id);
        if(isset($categoria)){
            if($categoria->ativo==true){
                $categoria->ativo = false;
                $categoria->save();
                return back()->with('mensagem', 'Categoria foi inativada com Sucesso!')->with('type', 'success');
            } else {
                $categoria->ativo = true;
                $categoria->save();
                return back()->with('mensagem', 'Categoria foi ativada com Sucesso!')->with('type', 'success');
            }
        } else {
            return back()->with('mensagem', 'Essa categoria não existe!')->with('type', 'warning');
        }
    }

    //PRODUTO
    public function indexProdutos()
    {
        $categorias = Categoria::all();
        $produtos = Produto::where('ativo',true)->orderBy('estoque')->orderBy('nome')->paginate(20);
        $view = "inicial";
        return view('cadastros.produtos',compact('categorias','produtos'));
    }

    public function filtroProdutos(Request $request)
    {
        $contador = 0;
        $query = Produto::query();
        if(isset($request->id)){
            $contador++;
            $query->where('id', $request->id);
        }
        if(isset($request->nome)){
            $contador++;
            $query->where('nome', 'LIKE', '%' . $request->nome . '%');
        }
        if(isset($request->estoque)){
            $contador++;
            $query->where('estoque', '<=', $request->estoque);
        }
        if(isset($request->categoria)){
            $contador++;
            $query->where('categoria_id', $request->categoria);
        }
        if(isset($request->ativo)){
            $contador++;
            $query->where('ativo', $request->ativo);
        }
        $produtos = $query->orderBy('estoque')->orderBy('nome')->paginate(100);
        if($contador==1){
            if($request->ativo==true){
                return redirect('/cadastros/produtos');
            }
        }
        $categorias = Categoria::all();
        $view = "filtro";
        return view('cadastros.produtos', compact('view','produtos','categorias'));
    }

    public function novoProduto(Request $request)
    {
        $produto = new Produto();
        if(isset($request->codigoBarras)){
            $produto->codigo_barras = $request->codigoBarras;
        }
        $produto->nome = $request->nome;
        $produto->embalagem = $request->embalagem;
        if(isset($request->marca)){
            $produto->marca = $request->marca;
        }
        $produto->preco_atual = str_replace(',', '.', $request->preco);
        //$produto->estoque = $request->estoque;
        $produto->categoria_id = $request->categoria;
        if($request->file('foto')!=""){
            $path = $request->file('foto')->store('fotos_produtos','public');
            $produto->foto = $path;
        }
        $produto->save();
        $prodPreco = new ProdutoPreco();
        $prodPreco->produto_id = $produto->id;
        $prodPreco->preco = $request->preco;
        $prodPreco->usuario = Auth::user()->name;
        $prodPreco->save();
        return back()->with('mensagem', 'Produto cadastrado com Sucesso!')->with('type', 'success');
    }

    public function editarProduto(Request $request, $id)
    {
        $produto = Produto::find($id);
        if(isset($produto)){
            if(isset($request->codigoBarras)){
                $produto->codigo_barras = $request->codigoBarras;
            }
            $produto->nome = $request->nome;
            $produto->embalagem = $request->embalagem;
            if(isset($request->marca)){
                $produto->marca = $request->marca;
            }
            if(isset($request->preco)){
                $prodPreco = new ProdutoPreco();
                $prodPreco->produto_id = $id;
                $prodPreco->preco = str_replace(',', '.', $request->preco);
                $prodPreco->usuario = Auth::user()->name;
                $prodPreco->save();
                $produto->preco_atual = str_replace(',', '.', $request->preco);
            }
            //$produto->estoque = $request->estoque;
            $produto->categoria_id = $request->categoria;
            if($request->file('foto')!=""){
                Storage::disk('public')->delete($produto->foto);
                $path = $request->file('foto')->store('fotos_produtos','public');
                $produto->foto = $path;
            }
            $produto->save();
            return back()->with('mensagem', 'Produto alterado com Sucesso!')->with('type', 'success');
        } else{
            return back()->with('mensagem', 'Esse produto não existe!')->with('type', 'warning');
        }
    }

    public function entradaProduto(Request $request, $id)
    {
        $prodEntrada = new ProdutoEntrada();
        $prodEntrada->produto_id = $id;
        $prodEntrada->quantidade_entrada = $request->qtd;
        $prodEntrada->custo = str_replace(',', '.', $request->custo);
        $prodEntrada->save();
        $produto = Produto::find($id);
        $produto->estoque += $request->qtd;
        $produto->save();
        return back()->with('mensagem', 'Entrada no produto efetuada com Sucesso!')->with('type', 'success');
    }

    public function ativarProduto($id)
    {
        $produto = Produto::find($id);
        if(isset($produto)){
            if($produto->ativo == true){
                $produto->ativo = false;
                $produto->save();
                return back()->with('mensagem', 'Produto foi inativado com Sucesso!')->with('type', 'success');
            } else {
                $produto->ativo = true;
                $produto->save();
                return back()->with('mensagem', 'Produto foi ativado com Sucesso!')->with('type', 'success');
            }
        } else {
            return back()->with('mensagem', 'Esse produto não existe!')->with('type', 'warning');
        }
    }

    //PAGAMENTO FORMAS
    public function indexPagamentoFormas()
    {
        $formas = PagamentoForma::orderBy('descricao')->get();
        return view('cadastros.pagamento_formas',compact('formas'));
    }

    public function novaPagamentoForma(Request $request)
    {
        $forma = new PagamentoForma();
        $forma->descricao = $request->descricao;
        if($request->juros==true){
            $forma->juros = $request->juros;
            $forma->tipo_juros = $request->tipoJuros;
            $forma->valor_juros = str_replace(',', '.', $request->valorJuros);
        } else {
            $forma->juros = false;
        }
        $forma->save();
        return back()->with('mensagem', 'Forma de Pagamento cadastrada com Sucesso!')->with('type', 'success');
    }

    public function editarPagamentoForma(Request $request, $id)
    {
        $forma = PagamentoForma::find($id);
        if(isset($forma)){
            $forma->descricao = $request->descricao;
            if($request->juros==true){
                $forma->juros = $request->juros;
                $forma->tipo_juros = $request->tipoJuros;
                $forma->valor_juros = str_replace(',', '.', $request->valorJuros);
            } else {
                $forma->juros = false;
                $forma->tipo_juros = null;
                $forma->valor_juros = 0;
            }
            $forma->save();
            return back()->with('mensagem', 'Forma de Pagamento alterada com Sucesso!')->with('type', 'success');
        } else{
            return back()->with('mensagem', 'Essa Forma de Pagamento não existe!')->with('type', 'warning');
        }
    }

    public function ativarPagamentoForma($id)
    {
        $forma = PagamentoForma::find($id);
        if(isset($forma)){
            if($forma->ativo==true){
                $forma->ativo = false;
                $forma->save();
                return back()->with('mensagem', 'Produto foi inativado com Sucesso!')->with('type', 'success');
            } else {
                $forma->ativo = true;
                $forma->save();
                return back()->with('mensagem', 'Produto foi ativado com Sucesso!')->with('type', 'success');
            }
        } else {
            return back()->with('mensagem', 'Esse produto não existe!')->with('type', 'warning');
        }
    }


    //CLIENTE
    public function indexClientes()
    {
        $clientes = Cliente::orderBy('nome')->paginate(20);
        $view = "inicial";
        return view('cadastros.clientes',compact('view','clientes'));
    }

    public function filtroClientes(Request $request)
    {
        $contador = 0;
        $query = Cliente::query();
        if(isset($request->id)){
            $contador++;
            $query->where('id', $request->id);
        }
        if(isset($request->nome)){
            $contador++;
            $query->where('nome', 'LIKE', '%' . $request->nome . '%');
        }
        if(isset($request->ativo)){
            $contador++;
            $query->where('ativo', $request->ativo);
        }
        $clientes = $query->orderBy('nome')->paginate(100);
        if($contador==1){
            if($request->ativo==true){
                return redirect('/cadastros/clientes');
            }
        }
        $view = "filtro";
        return view('cadastros.clientes', compact('view','clientes'));
    }

    public function novoCliente(Request $request)
    {
        $cliente = new Cliente();
        $cliente->nome = $request->nome;
        if(isset($request->cpf)){
            $cliente->cpf = $request->cpf;
        }
        if(isset($request->dtn)){
            $cliente->dtn = $request->dtn;
        }
        if(isset($request->cpf)){
            $cliente->cpf = $request->cpf;
        }
        if(isset($request->tel1)){
            $cliente->tel1 = $request->tel1;
        }
        if(isset($request->tel2)){
            $cliente->tel2 = $request->tel2;
        }
        if(isset($request->cep)){
            $cliente->cep = $request->cep;
        }
        if(isset($request->rua)){
            $cliente->rua = $request->rua;
        }
        if(isset($request->numero)){
            $cliente->numero = $request->numero;
        }
        if(isset($request->complemento)){
            $cliente->complemento = $request->complemento;
        }
        if(isset($request->bairro)){
            $cliente->bairro = $request->bairro;
        }
        if(isset($request->cidade)){
            $cliente->cidade = $request->cidade;
        }
        if(isset($request->uf)){
            $cliente->uf = $request->uf;
        }
        $cliente->save();
        return back()->with('mensagem', 'Cliente cadastrado com Sucesso!')->with('type', 'success');
    }

    public function editarCliente(Request $request, $id)
    {
        $cliente = Cliente::find($id);
        if(isset($cliente)){
            $cliente->nome = $request->nome;
            $cliente->cpf = $request->cpf;
            $cliente->dtn = $request->dtn;
            $cliente->cpf = $request->cpf;
            $cliente->tel1 = $request->tel1;
            $cliente->tel2 = $request->tel2;
            $cliente->cep = $request->cep;
            $cliente->rua = $request->rua;
            $cliente->numero = $request->numero;
            $cliente->complemento = $request->complemento;
            $cliente->bairro = $request->bairro;
            $cliente->cidade = $request->cidade;
            $cliente->uf = $request->uf;
            $cliente->save();
            return back()->with('mensagem', 'Cliente alterado com Sucesso!')->with('type', 'success');
        } else{
            return back()->with('mensagem', 'Esse Cliente não existe!')->with('type', 'warning');
        }
    }

    public function ativarCliente($id)
    {
        $cliente = Cliente::find($id);
        if(isset($cliente)){
            if($cliente->ativo == true){
                $cliente->ativo = false;
                $cliente->save();
                return back()->with('mensagem', 'Cliente foi inativado com Sucesso!')->with('type', 'success');
            } else {
                $cliente->ativo = true;
                $cliente->save();
                return back()->with('mensagem', 'Cliente foi ativado com Sucesso!')->with('type', 'success');
            }
        } else {
            return back()->with('mensagem', 'Esse Cliente não existe!')->with('type', 'warning');
        }
    }

    //USERS
    public function indexUsers()
    {
        $users = User::orderBy('name')->paginate(10);
        return view('cadastros.users', compact('users'));
    }

    public function novoUser(Request $request)
    {
        $request->validate([
            'email' => 'unique:users',
            'password' => 'min:8',
            'password_confirmation' => 'required|same:password',
        ], $mensagens =[
            'email.unique' => 'Já existe um usuário com esse login!',
            'password.min' => 'A senha deve conter no mínimo 8 caracteres!',
            'password_confirmation.same' => 'As senhas não conferem!',
        ]);
        
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        return back()->with('mensagem', 'Usuário Cadastrado com Sucesso!');
    }

    public function editarUser(Request $request, $id)
    {
        $user = User::find($id);
        if(isset($user)){
            $user->name =$request->name;
            $user->email =$request->email;
            if(isset($request->password)){
                $user->password = Hash::make($request->password);
            }
            $user->save();
        }
        return back()->with('mensagem', 'Usuário Alterado com Sucesso!');
    }

    public function ativarUser($id)
    {
        $user = User::find($id);
        if(isset($user)){
            if($user->ativo==1){
                $user->ativo = false;
                $user->save();
                return back()->with('mensagem', 'Usuário Inativado com Sucesso!')->with('type', 'success');
            } else {
                $user->ativo = true;
                $user->save();
                return back()->with('mensagem', 'Usuário Ativado com Sucesso!')->with('type', 'success');
            }
        }
        return back();
    }
}
