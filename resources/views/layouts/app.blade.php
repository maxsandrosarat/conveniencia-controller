<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>{{$page ?? 'Conveniência - Controller'}}</title>
    <link rel="shortcut icon" href="/storage/logo.png"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#E6E6FA">
    <meta name="apple-mobile-web-app-status-bar-style" content="#E6E6FA">
    <meta name="msapplication-navbutton-color" content="#E6E6FA">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <div class="container">
            <header>
                @component('components.componente_navbar', ["current"=>$current ?? ''])
                @endcomponent
            </header>
            <main>
                @hasSection ('body')
                    @yield('body')   
                @endif
            </main>
            @component('components.componente_footer')
            @endcomponent
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="{{ asset('js/public.js') }}"></script>
</body>
</html>
