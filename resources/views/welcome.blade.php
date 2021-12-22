<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#E6E6FA">
        <meta name="apple-mobile-web-app-status-bar-style" content="#E6E6FA">
        <meta name="msapplication-navbutton-color" content="#E6E6FA">

        <title>{{$page ?? 'Conveniência - Controller'}}</title>
        <link rel="shortcut icon" href="/storage/logo.png"/>
        
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    </head>
    <body>
        <div class="container">
            <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
              <a href="/" class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none">
                <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap">
              </a>
        
              <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
                @if(Route::has('login'))
                    @auth("web")
                        <li><a href="/home" class="nav-link px-2 link-secondary"><h1><b>Home</b></h1></a></li>
                    @endauth
                @endif
              </ul>
        
              <div class="col-md-3 text-end">
                @auth("web")
                @else
                    <a href="{{ route('login') }}" type="button" class="btn btn-outline-dark me-2">Login</a>
                    {{--  @if (Route::has('register'))
                        <a href="{{ route('register') }}" type="button" class="btn btn-dark">Sign-up</a>
                    @endif  --}}
                @endauth
              </div>
            </header>
          </div>
            <div class="content">
                <b><h2><font face="Freestyle Script">Seja Bem-vindo!</font></h2></b>
                <div class="title m-b-md">
                    <img class="img-fluid" src="/storage/logo.png" alt="logo" width="30%">
                </div>
                @auth("web")
                    <b> <h3>Você está logado como  {{Auth::user()->name}}  !</h3></b>
                @else
                    <b> <h3>Você não está logado! Por gentileza faça login.</h3></b>
                @endauth
            </div>
        </div>
        <!-- Option 1: Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </body>
</html>