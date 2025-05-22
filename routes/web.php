<?php

use App\Models\User;
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
    $user->password = $request->password;
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
})->name('cadastra-equipe');