<?php

namespace App\Http\Controllers;

use App\Models\ProdutoSaida;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        
        $lucro_hoje = ProdutoSaida::where('motivo','venda')->whereBetween('created_at',[date("Y/m/d")." 00:00", date("Y/m/d")." 23:59"])->sum('lucro');
        $lucro_anterior = ProdutoSaida::where('motivo','venda')->whereBetween('created_at',[gmdate("Y-m-d", time()-(3600*27))." 00:00", gmdate("Y-m-d", time()-(3600*27))." 23:59"])->sum('lucro');
        $lucro_mes = ProdutoSaida::where('motivo','venda')->whereBetween('created_at',[date("Y")."-".date("m")."-01"." 00:00", date("Y")."-".date("m")."-".date("t", mktime(0,0,0,date("m"),'01',date("Y")))." 23:59"])->sum('lucro');
        return view('home', compact('lucro_hoje','lucro_anterior','lucro_mes'));
    }
}
