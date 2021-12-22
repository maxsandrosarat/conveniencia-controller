@extends('layouts.app', ["current"=>"login"])

@section('body')
@php
	$page = "Login";
@endphp
<div class="text-center">
	@auth("web")
        <b> <h3>Você está logado como  {{Auth::user()->name}}  !</h3></b>
		<h5>Acesse sua página principal: <a href="{{ url('/home') }}">Home</a></h5>
		<h5>Ou faça logout: <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></h5>
		<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    @else
				<div class="container">
					<div class="row justify-content-center">
						<div class="col-md-4">
							<div class="card">
								<div class="card-body">
									@if(session('mensagem'))
										<div class="alert alert-danger alert-dismissible fade show" role="alert">
											{{session('mensagem')}}
											<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
										</div>
									@endif
									<form method="POST" action="{{ route('login') }}">
										@csrf
										<img class="mb-4" src="/storage/logo.png" alt="logo" width="80px">
										<h1 class="h3 mb-3 fw-normal">Faça Login</h1>
										<div class="form-floating">
											<input type="email" class="form-control" name="email" id="floatingInput" placeholder="name@example.com">
											<label for="floatingInput">Email</label>
										</div>
										<div class="form-floating">
											<input type="password" class="form-control" name="password" id="floatingPassword" placeholder="Password">
											<label for="floatingPassword">Senha</label>
											<button id="botao-senha" class="badge bg-success rounded-pill" type="button" data-toggle="tooltip" data-placement="bottom" title="Exibir Senha" onclick="mostrarSenha()"><i id="icone-senha"class="material-icons white">visibility</i></button>
										</div>
										<br/>
										<div class="checkbox mb-3">
											<div class="form-check form-switch">
												<label class="form-check-label" for="flexSwitchCheckDefault">
													<input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" value="remember-me" name="remember" {{ old('remember') ? 'checked' : '' }}>
													Manter conectado
												</label>
											</div>
										</div>
										<button class="w-100 btn btn-lg btn-primary" type="submit">Login</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
    @endauth
  </div>
@endsection