<?php
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/login-facebook', function () {
    return Socialite::driver('facebook')->redirect();
});

Route::get('/facebook-callback', function () {
    $user = Socialite::driver('facebook')->user();
    
    $userExists = User::where('external_id', $user->id)->where('external_auth', 'facebook')->exists();
    if($userExists){
        Auth::login($userExists);
    }else{
        $userNew = User::create([
            'name'=> $user -> name,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'external_id' => $user->external_id,
            'external_auth' => 'facebook',
        ]);

        Auth::login($userNew);
    }
    return Socialite::driver('facebook')
    ->redirectUrl('https://testf.up.railway.app/auth/callback')
    ->redirect();
    //$userExists = User::where('external_id', $user->id)->where('external_auth','facebook')->exists();
});
