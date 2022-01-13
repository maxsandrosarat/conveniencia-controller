var id = 0;

function validarSenhaForca(){
    var senha = $("#senhaForca").val();
    var forca = 0;
    if((senha.length >= 4) && (senha.length <= 8)){
        forca += 10;
    }else if(senha.length > 8){
        forca += 25;
    }
    if((senha.length >= 5) && (senha.match(/[a-z]+/))){
        forca += 10;
    }
    if((senha.length >= 6) && (senha.match(/[A-Z]+/))){
        forca += 20;
    }
    if((senha.length >= 7) && (senha.match(/[@#$%&;*]/))){
        forca += 25;
    }
    if(senha.match(/([1-9]+)\1{1,}/)){
        forca += -25;
    }
    mostrarForca(forca);
}

function mostrarForca(forca){
    if(forca < 30 ){
        $("#erroSenhaForca").html('<div class="progress"><div class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div></div>');
    }else if((forca >= 30) && (forca < 50)){
        $("#erroSenhaForca").html('<div class="progress"><div class="progress-bar progress-bar-striped bg-warning" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div></div>');
    }else if((forca >= 50) && (forca < 70)){
        $("#erroSenhaForca").html('<div class="progress"><div class="progress-bar progress-bar-striped bg-info" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div></div>');
    }else if((forca >= 70) && (forca < 100)){
        $("#erroSenhaForca").html('<div class="progress"><div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div></div>');
    }
}

//view(auth/login)
function mostrarSenha(){
    if($("#floatingPassword").attr('type')=="password"){
        $("#floatingPassword").attr('type', "text");
        $("#icone-senha").html("visibility_off");
        $("#botao-senha").removeClass();
        $("#botao-senha").addClass("badge bg-warning rounded-pill");
        $("#botao-senha").attr('title', "Ocultar Senha");
    } else {
        $("#floatingPassword").attr('type', "password");
        $("#icone-senha").html("visibility");
        $("#botao-senha").removeClass();
        $("#botao-senha").addClass("badge bg-success rounded-pill");
        $("#botao-senha").attr('title', "Mostrar Senha");
    }
}

function validarPreco(campo){
    var preco = $('#'+ campo +'').val().replace(',','.');
    var length = preco.length;
    if(length>0){
        $('#'+ campo +'').val(parseFloat(preco));
    } else {
        $('#'+ campo +'').val("");
    }
}

function validarCodBarras(campo){
    var codigo = $('#'+ campo +'').val();
    var length = codigo.length;
    if(length>0){
        if(length==13){

        } else if((length>=1 && length<=12) || length>13){
            alert('Código de Barras Inválido, Padrão: 13 digitos!');
            $('#'+ campo +'').val("");
        }
    } else {
        $('#'+ campo +'').val("");
    }
}

function formatarCpf() {
    var numero = $('#cpf').val();
    var length = numero.length;
    var cpfFormatado;
    if(length>0){
        if (length == 11) {
            cpfFormatado = numero.substring(0, 3) + '.' + numero.substring(3, 6) + '.' + numero.substring(6, 9) + '-' + numero.substring(9, 11);
        } else {
            $('#cpf').val("");
            alert("CPF inválido, digite apenas os 11 números.");
        }
    } else {
        $('#cpf').val("");
    }
    $('#cpf').val(cpfFormatado);
}

function formataNumeroTelefone(id) {
    var numero = $('#telefone'+ id +'').val();
    var length = numero.length;
    var telefoneFormatado;
    if(length>0){
        if (length == 10) {
        telefoneFormatado = '(' + numero.substring(0, 2) + ') ' + numero.substring(2, 6) + '-' + numero.substring(6, 10);
        } else if (length == 11) {
        telefoneFormatado = '(' + numero.substring(0, 2) + ') ' + numero.substring(2, 7) + '-' + numero.substring(7, 11);
        } else {
            $('#telefone'+ id +'').val("");
            alert("Número inválido, digite apenas os números com DDD.");
        }
    } else{
        $('#telefone'+ id +'').val("");
    }
    $('#telefone'+ id +'').val(telefoneFormatado);
}

function limpa_formulário_cep() {
    $('#rua'+ id +'').val("");
    $('#bairro'+ id +'').val("");
    $('#cidade'+ id +'').val("");
    $('#uf'+ id +'').val("");
    $('#ibge'+ id +'').val("");
}

function meu_callback(conteudo) {
    if (!("erro" in conteudo)) {
        $('#rua'+ id +'').val(conteudo.logradouro);
        $('#bairro'+ id +'').val(conteudo.bairro);
        $('#cidade'+ id +'').val(conteudo.localidade);
        $('#uf'+ id +'').val(conteudo.uf);
        $('#ibge'+ id +'').val(conteudo.ibge);
    }
    else {
        limpa_formulário_cep();
        alert("CEP não encontrado.");
    }
}

function pesquisacep(valor, id) {
    window['id'] = id;
    var cep = valor.replace(/\D/g, '');
    if (cep != "") {
        var validacep = /^[0-9]{8}$/;
        if(validacep.test(cep)) {
            $('#rua'+ id +'').val("...");
            $('#bairro'+ id +'').val("...");
            $('#cidade'+ id +'').val("...");
            $('#uf'+ id +'').val("...");
            $('#ibge'+ id +'').val("...");
            var script = document.createElement('script');
            script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';
            document.body.appendChild(script);
        }
        else {
            limpa_formulário_cep();
            alert("Formato de CEP inválido.");
        }
    }
    else {
        limpa_formulário_cep();
    }
};

function buscarProdutos(){
    var nomeProd = $('#nomeProd').val();
    var idProd = $('#idProd').val();
    var codProd = $('#codBarProd').val();
    var nome = "";
    var id = "";
    var cod = "";
    if(nomeProd.length>=3){
        nome = nomeProd;
    }
    if(idProd.length>=1){
        id = idProd;
    }
    if(codProd.length>=1){
        cod = codProd;
    }
    if(nome=="" && id=="" && cod==""){
        $('#listaProdutos>li').remove();
    } else{
        $.get('/cadastros/produtos/busca',{nome: nome, id: id, cod: cod},function (data) {
            $('#listaProdutos>li').remove();
            for(i=0; i<data.length; i++){
                s = "";
                s = '<li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" id="produto'+ data[i].id + '"><input type="checkbox" class="form-check-input me-1" id="produto'+ data[i].id + '" name="produtos[]" value="'+ data[i].id +'">' +
                    '<label class="form-check-label" for="produto'+ data[i].id + '"> '+ data[i].nome +' '+ data[i].marca +' ('+ data[i].embalagem +') - R$ '+ data[i].preco_atual.replace('.',',') +' </label>' +
                    '<span class="badge bg-primary rounded-pill">'+ data[i].estoque +'</span>' +
                    '</li>'
                $('#listaProdutos').append(s);
            }
        });
    }
}

function delay(fn, ms) {
    let timer = 0
    return function(...args) {
      clearTimeout(timer)
      timer = setTimeout(fn.bind(this, ...args), ms || 0)
    }
}

$(document).on('change', '#cliente', function(){
    var venda = $('#venda').val();
    var cliente = this.value;
    $.get('/vendas/cliente',{venda: venda, cliente: cliente},function (data) {});
});

    $('#nomeProd').keyup(function(){
        buscarProdutos();
    });

    $('#idProd').keyup(function(){
        buscarProdutos();
    });

    $('#codBarProd').keyup(function(){
        buscarProdutos();
    });

    function converterReal(valor){
        var preco = parseFloat(valor);
        return preco.toLocaleString("pt-BR", { style: "currency" , currency:"BRL"});
    }

    function atualizaTotais(venda){
        $.get('/vendas/id/'+ venda +'',{},function (data) {
            var valorFinal = parseFloat(data.total_final) + parseFloat(data.juros);
            $('#valorTotal>b').remove();
            $('#qtdTotal>b').remove();
            $('#descontoTotal>b').remove();
            $('#totalGeral>b').remove();
            $('#valorTotalFinal'+ venda +'>b').remove();
            $('#jurosFinal'+ venda +'>b').remove();
            $('#valorFinal'+ venda +'>b').remove();
            $('#valorTotal').append('<b>'+ converterReal(data.valor_total) +'</b>');
            $('#qtdTotal').append('<b>'+ data.total_produtos +'</b>');
            $('#descontoTotal').append('<b>'+ converterReal(data.desconto_total) +'</b>');
            $('#totalGeral').append('<b>'+ converterReal(data.total_final) +'</b>');
            $('#valorTotalFinal'+ venda +'').append('<b>'+ converterReal(data.total_final) +'</b>');
            $('#jurosFinal'+ venda +'').append('<b>'+ converterReal(data.juros) +'</b>');
            $('#valorFinal'+ venda +'').append('<b>'+ converterReal(valorFinal) +'</b>');
            if(data.total_produtos==0 || $('#pagamento').val()==""){
                $('#btn-concluir').addClass("disabled");
            } else {
                $('#btn-concluir').removeClass("disabled");
            }
        });
    }

    function atualizaProduto(venda, produto){
        $.get('/cadastros/produtos/qtd',{venda: venda, produto: produto},function (data) {
            var total = (data.produto.preco_atual * data.qtd) - (data.desconto * data.qtd);
            $('#qtd'+ produto +'>b').remove();
            $('#total'+ produto +'>b').remove();
            $('#qtd'+ produto +'').append('<b>'+ data.qtd +'</b>');
            $('#total'+ produto +'').append('<b>'+ converterReal(total) +'</b>');
            atualizaTotais(venda);
        });
    }

    function ZenkakuToHankaku(str) {
        return str.replace(/[Ａ-Ｚａ-ｚ０-９]/g, function(s){return String.fromCharCode(s.charCodeAt(0)-0xFEE0)});
    }

    function NumberOnly(str) {
        return ZenkakuToHankaku(str).replace(/[^0-9\.]+/g, '');
    }

    $(document).on('keyup', 'input[name="desconto"]', delay(function () {
        var venda = $('#venda').val();
        var produto = this.id;
        var valor = NumberOnly(this.value.replace(',','.'));
        $('#'+ produto +'').val(valor);
        if(valor.length==0){
            valor = 0;
        }
        $.get('/cadastros/produtos/desconto',{venda: venda, produto: produto, valor: valor},function (data) {
            atualizaProduto(venda, produto);
        });
    }, 500));

    function removerProduto(venda, produto, qtd){
        $.get('/cadastros/produtos/remover',{venda: venda, produto: produto, qtd: qtd},function (data) {
            if(data==0){
                $('#listaProdutosSelecionados #produto'+ produto +'').remove();
                atualizaTotais(venda);
            } else {
                atualizaProduto(venda, produto);
            }
        });
    }

    function adicionarProduto(venda, produto){
        $.get('/cadastros/produtos/adicionar',{venda: venda, produto: produto},function (data) {
            if(data==0){
                alert('A quantidade em estoque desse produto já foi atingida, verifique!');
                atualizaProduto(venda, produto);
            } else {
                atualizaProduto(venda, produto);
            }
        });
    }

    $(document).on('change', 'input[type=checkbox]', function(){
        if(this.checked) {
            var venda = $('#venda').val();
            var produto = this.value;
            $.get('/cadastros/produtos/selecionado',{produto: produto, venda: venda},function (data) {
                if(data!=0){
                    s = "";
                    s = '<tr id="produto'+ data.id + '">' +
                            '<td>' +
                                '<input type="checkbox" class="form-check-input me-1" id="produto'+ data.id + '" name="produtos[]" value="'+ data.id +'" checked>' +
                            '</td>' +
                            '<td>'+ data.nome +' '+ data.marca +' ('+ data.embalagem +')</td>' +
                            '<td>'+ converterReal(data.preco_atual) +'</td>' +
                            '<td>' +
                                '<div class="row center-align">' +
                                    '<a class="col md-4" href="javascript:void(0);" onclick="removerProduto('+ venda +',' + data.id +', 1)">' +
                                        '<i class="material-icons red">remove_circle_outline</i>' +
                                    '</a>' +
                                    '<span id="qtd'+ data.id +'" class="col md-4"><b>1</b></span>' +
                                    '<a class="col md-4" href="javascript:void(0);" onclick="adicionarProduto('+ venda +','+ data.id +')">' +
                                        '<i class="material-icons">add_circle_outline</i>' +
                                    '</a>' +
                                    '</div>' +
                                '</td>' +
                            '<td>' +
                                '<input class="form-control" id="'+ data.id +'" type="text" name="desconto" placeholder="Desconto por Unidade">' +
                            '</td>' +
                            '<td id="total'+ data.id +'"><b>'+ converterReal(data.preco_atual) +'</b></td>' +
                        '</tr>'
                    $('#listaProdutos #produto'+ data.id +'').remove();
                    var elem = document.querySelector('#listaProdutosSelecionados>tr #produto'+ data.id +'');
                    if (elem) {
                        alert('Esse Produto já foi adicionado!');
                    } else {
                        $('#listaProdutosSelecionados').append(s);
                    }
                } else {
                    alert('Esse Produto já foi adicionado, aumente a quantidade!');
                    $('#listaProdutos #produto'+ produto +'').remove();
                }
                atualizaProduto(venda, produto);
            });
        } else {
            $('#listaProdutosSelecionados #produto'+ this.value +'').remove();
            var venda = $('#venda').val();
            var produto = this.value;
            removerProduto(venda, produto, 0);
        }
    });


    $(document).on('change', '#pagamento', function(){
        var venda = $('#venda').val();
        var pagamento = this.value;
        $.get('/vendas/pagamento',{venda: venda, pagamento: pagamento},function (data) {
            atualizaTotais(venda);
        });
    });

    function concluirVenda(pago) {
        $('#form-venda input[name="pago"]').val(pago);
        $('#form-venda').submit();
    }

    function atualizaTotaisVendas(venda){
        $.get('/vendas/id/'+ venda +'',{},function (data) {
            var valorFinal = parseFloat(data.total_final) + parseFloat(data.juros);
            $('#valorTotalFinal'+ venda +'>b').remove();
            $('#jurosFinal'+ venda +'>b').remove();
            $('#valorFinal'+ venda +'>b').remove();
            $('#valorTotalFinal'+ venda +'').append('<b>'+ converterReal(data.total_final) +'</b>');
            $('#jurosFinal'+ venda +'').append('<b>'+ converterReal(data.juros) +'</b>');
            $('#valorFinal'+ venda +'').append('<b>'+ converterReal(valorFinal) +'</b>');
        });
    }

    $(document).on('change', '#forma_pagamento', function(){
        var venda = this.title;
        var pagamento = this.value;
        $.get('/vendas/pagamento',{venda: venda, pagamento: pagamento},function (data) {
            atualizaTotaisVendas(venda);
        });
    });

    function adicionarProdutoEx(){
        var prod = $('#input-prodEx').val();
        s = "";
        s = '<li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">' +
                '<input type="checkbox" checked class="form-check-input" name="produtosExtras[]" value="'+ prod +'">' +
                '<label class="form-check-label">'+ prod +'</label>' +
                '<span class="badge badge-primary badge-pill">0</span>' +
            '</li>'

        $('#lista-produtos').append(s);
        $('#input-prodEx').val("");
    }

