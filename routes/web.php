<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\DB;
use App\Models\User;

use App\Http\Controllers\Profile\AvatarController;
use Illuminate\Support\Facades\Route;
use OpenAI\Laravel\Facades\OpenAI;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
   return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/avatar', [AvatarController::class, 'update'])->name('profile.avatar');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/openai',function(){
    $result = OpenAI::completions()->create([
        'model' => 'gpt-3.5-turbo',
        'prompt' => 'PHP is'
    ]);
    echo $result['choices'][0]['text'];
});


 
Route::post('/auth/redirect', function () {
    return Socialite::driver('github')->redirect();
})->name('login.github');
 

Route::get('/auth/callback', function () {
    $user = Socialite::driver('github')->user();
    User::firstOrCreate(
        ['email'=> $user->email],
        ['name'=> $user->name,
        'password'=>'password']
    );
    Auth::login($user); 
    return redirect('/dashboard');
    // $user->token
});


Route::middleware('auth')->group(function () {
    Route::get('/ticket/create',function(){
        return view('ticket.create');
    });
});


