<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'cadastros'], function() {
    Route::get('/', 'CadastroController@indexCadastros');

    Route::group(['prefix' => 'categorias'], function() {
        Route::get('/', 'CadastroController@indexCategorias');
        Route::post('/', 'CadastroController@novaCategoria');
        Route::post('/editar/{id}', 'CadastroController@editarCategoria');
        Route::get('/ativar/{id}', 'CadastroController@ativarCategoria');
    });

    Route::group(['prefix' => 'produtos'], function() {
        Route::get('/', 'CadastroController@indexProdutos');
        Route::get('/filtro', 'CadastroController@filtroProdutos');
        Route::post('/', 'CadastroController@novoProduto');
        Route::post('/editar/{id}', 'CadastroController@editarProduto');
        Route::post('/entrada/{id}', 'CadastroController@entradaProduto');
        Route::get('/ativar/{id}', 'CadastroController@ativarProduto');
        
        //VENDA
        Route::get('/busca', 'VendaController@buscarProduto');
        Route::get('/selecionado', 'VendaController@selecionarProduto');
        Route::get('/qtd', 'VendaController@qtdProdutoVenda');
        Route::get('/remover', 'VendaController@removerProduto');
        Route::get('/adicionar', 'VendaController@adicionarProduto');
        Route::get('/desconto', 'VendaController@descontoProduto');
    });

    Route::group(['prefix' => 'pagamentoFormas'], function() {
        Route::get('/', 'CadastroController@indexPagamentoFormas');
        Route::post('/', 'CadastroController@novaPagamentoForma');
        Route::post('/editar/{id}', 'CadastroController@editarPagamentoForma');
        Route::get('/ativar/{id}', 'CadastroController@ativarPagamentoForma');
    });

    Route::group(['prefix' => 'clientes'], function() {
        Route::get('/', 'CadastroController@indexClientes');
        Route::get('/filtro', 'CadastroController@filtroClientes');
        Route::post('/', 'CadastroController@novoCliente');
        Route::post('/editar/{id}', 'CadastroController@editarCliente');
        Route::get('/ativar/{id}', 'CadastroController@ativarCliente');
        Route::get('/inativar/{id}', 'CadastroController@inativarCliente');
    });

    Route::group(['prefix' => 'user'], function() {
        Route::get('/', 'CadastroController@indexUsers');
        Route::post('/', 'CadastroController@novoUser');
        Route::post('/editar/{id}', 'CadastroController@editarUser');
        Route::get('/ativar/{id}', 'CadastroController@ativarUser');
    });
});

Route::group(['prefix' => 'vendas'], function() {
    Route::get('/', 'VendaController@indexVendas');
    Route::get('/nova', 'VendaController@novaVenda');
    Route::get('/filtro', 'VendaController@filtroVendas');
    Route::post('/', 'VendaController@cadastrarVenda');
    Route::post('/cancelar/{id}', 'VendaController@cancelarVenda');
    Route::get('/pagar/{id}', 'VendaController@pagarVenda');
    Route::get('/id/{id}', 'VendaController@buscarVenda');
    Route::get('/cliente', 'VendaController@cliente');
    Route::get('/pagamento', 'VendaController@pagamento');
});

Route::group(['prefix' => 'despesas'], function() {
    Route::get('/', 'DespesaController@despesas');

    Route::group(['prefix' => 'lancamentos'], function() {
        Route::get('/dia', 'DespesaController@indexDespesasDia');
        Route::get('/mes', 'DespesaController@indexDespesasMes');
        Route::get('/', 'DespesaController@indexDespesas');
        Route::post('/', 'DespesaController@cadastrarDespesa');
        Route::post('/pagar/{id}', 'DespesaController@pagarDespesa');
        Route::post('/editar/{id}', 'DespesaController@editarDespesa');
        Route::get('/apagar/{id}', 'DespesaController@apagarDespesa');
        Route::get('/filtro', 'DespesaController@filtroDespesa');
    });
});

Route::group(['prefix' => 'relatorios'], function() {
    Route::get('/', 'RelatorioController@index');
    Route::get('/estoque', 'RelatorioController@estoque');
    Route::get('/estoque/entradas', 'RelatorioController@entradas');
    Route::get('/estoque/entradas/filtro', 'RelatorioController@entradasFiltro');
    Route::get('/estoque/saidas', 'RelatorioController@saidas');
    Route::get('/estoque/saidas/filtro', 'RelatorioController@saidasFiltro');
    Route::get('/vendas', 'RelatorioController@indexVendas');
    Route::get('/vendas/produtos', 'RelatorioController@vendasProdutos');
    Route::get('/vendas/produtos/filtro', 'RelatorioController@vendasProdutosFiltro');
    Route::get('/vendas/clientes', 'RelatorioController@vendasClientes');
    Route::get('/vendas/clientes/filtro', 'RelatorioController@vendasClientesFiltro');
    Route::get('/vendas/clientesProdutos', 'RelatorioController@vendasClientesProdutos');
    Route::get('/vendas/clientesProdutos/filtro', 'RelatorioController@vendasClientesProdutosFiltro');

    Route::group(['prefix' => 'listaCompras'], function() {
        Route::get('/', 'RelatorioController@indexListaCompras');
        Route::post('/', 'RelatorioController@novaListaCompra');
        Route::get('/nova', 'RelatorioController@selecionarListaCompra');
        Route::get('/pdf/{id}', 'RelatorioController@gerarPdfListaCompra');
        Route::get('/apagar/{id}', 'RelatorioController@apagarListaCompra');
        Route::get('/removerItem/{p}/{d}', 'RelatorioController@removerItem');
        Route::get('/removerItemExtra/{p}/{d}', 'RelatorioController@removerItemExtra');
    });
});




