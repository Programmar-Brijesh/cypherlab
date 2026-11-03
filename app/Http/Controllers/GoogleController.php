<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Session;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')
            ->stateless()
            ->user();

        $user = User::where('email', $googleUser->email)->first();

        if ($user) {

            if (!$user->google_id) {
                $user->google_id = $googleUser->id;
                $user->is_verified = true;
                $user->save();
            }
        } else {

            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'is_verified' => true,
            ]);
        }

        Session::put('user', $user);

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
