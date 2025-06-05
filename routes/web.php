<?php

use App\Models\User;
use App\Models\Equipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/inicio', function () {
    return view('inicio');
})->name('inicio');


Route::get('/criar-conta', function () {
    return view('criar-conta');
})->name('criar-conta');

Route::post('/salva-conta',
function (Request $request) {
    //dd($request);
    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = bcrypt($request->password); // **IMPORTANTE: HASH DA SENHA**
    $user->save();

    return redirect(route('login'));


})->name('salva-conta');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/logar', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        return redirect()->intended('dashboard');
    }

    return back()->withErrors([
        'email' => 'O email e senha digitados não são válidos',
    ])->onlyInput('email');
})->name('logar');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard')->middleware('auth');

Route::get('/cadastra-equipe', function () {
    return view('cadastra-equipe');
})->name('cadastra-equipe')->middleware('auth');

Route::post('/logout', function (Request $request) { 
    Auth::logout(); 

    $request->session()->invalidate(); 
    $request->session()->regenerateToken(); 

    return redirect('/'); 
})->name('logout');

Route::post('/salva-equipe', function (Request $request) { 
//dd($request)/;
$equipe = new Equipe();
$equipe->nome = $request->nome;
$equipe->email = $request->email;
$equipe->formacao = $request->formacao;
$equipe->experiencia = $request->experiencia;
$equipe->save();


return "Equipe salva com sucesso!!!";

})->name('salva-equipe')->middleware('auth');

Route::get('/lista-equipe', function () {
    $equipe = Equipe::all();
    return view('lista-equipe',compact('equipe'));
})->name('lista-equipe');

