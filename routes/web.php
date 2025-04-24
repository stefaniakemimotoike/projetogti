<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

Route::get('/', function () {
    return view('welcome');
});

// Tela de cadastro
Route::get('/criar-conta', function () {
    return view('criar-conta');

    return redirect(route('login'));

});

// Salva usuário
Route::post('/salva-conta', function (Request $request) {
    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = Hash::make($request->password); // senha criptografada
    $user->save();

    return redirect(route('login')); // redireciona ao login
})->name('salva-conta');

/// Tela de login
Route::get('/login', function () {
    return view('login');
})->name('login');


// Validação de login (POST)
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('dashboard');
    }

    return back()->withErrors([
        'email' => 'Credenciais inválidas.',
    ])->onlyInput('email');
});



// Dashboard (pós login)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::post('/logar', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    // Tenta autenticar o usuário
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();  // Regenera a sessão para evitar ataques de fixação de sessão
        return redirect()->intended(route('dashboard'));  // Redireciona para o dashboard
    }

    return back()->withErrors([
        'email' => 'Credenciais inválidas.',
    ])->onlyInput('email');
})->name('logar');


// Rotas de teste
Route::get('/teste', function () {
    return 'O código foi testado';
});

Route::get('/usuario/{nome}', function ($nome) {
    return 'o usuário atual é:'.$nome;
});

// Rota para sair (logout)
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
})->name('logout');


Route::get('/soma/{num1}/{num2}', function ($num1, $num2) {
    return 'a soma é: '.$num1 + $num2;
});

Route::get('/divisão/{num1}/{num2}', function ($num1, $num2) {
    return 'a divisão é: '.$num1 / $num2;
});

Route::get('/subtração/{num1}/{num2}', function ($num1, $num2) {
    return 'a subtração é: '.$num1 - $num2;
});

Route::get('/multiplicação/{num1}/{num2}', function ($num1, $num2) {
    return 'a multiplicação é: '.$num1 * $num2;
});