<html>
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <title>Lista de Compras - {{date("d/m/Y", strtotime($lista->data))}}</title>
        <link rel="shortcut icon" href="/storage/favicon.png"/>
    </head>
    <body>
    <div class="card border">
        <div class="card-body">
            <h4 class="card-title">Lista de Compras - {{date("d/m/Y", strtotime($lista->data))}}</h4>
            <h5>Solicitante: {{$lista->usuario}}</h5>
            <table class="table table-striped table-sm">
                <thead class="thead-dark">
                    <tr>
                        <th style="text-align: center;">Produto(s)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($produtos as $prod)
                    <tr>
                        <td>{{$prod->produto->nome}}</td>
                    </tr>
                    @endforeach
                    @foreach ($produtoExtras as $prod)
                    <tr>
                        <td>{{$prod->nome}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>