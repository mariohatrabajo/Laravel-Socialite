<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleLoginController extends Controller
{
    
    /**
     * Redirecciona al OAuth provider de google
     */
    function redirectToGoogle() {
        // ->redirect() redirecciona al servicio de autenticacion de google
        return Socialite::driver('google')->redirect();
    }

    /**
     * Vuelve a nuestra app despues de autenticarse
     */
    function handleGoogleCallback() {
        // ->user() Obtiene los datos de usuario
        $googleUser = Socialite::driver('google')->user();
        $user = User::where('email', $googleUser->email)->first();
        // Si no existe un user con ese email, creamos un user con esos datos
        if(!$user) {
            $user = User::create(['name' => $googleUser->name, 
                                  'email' => $googleUser->email, 
                                  'password' => '1234']);
        }
        // Hacemos login con ese user
        Auth::login($user);

        return redirect(route('dashboard'));
    }

    function logout() {
        Auth::logout();
        return redirect(route('welcome'));
    }
}
