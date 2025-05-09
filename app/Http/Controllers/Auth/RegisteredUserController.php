<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Ramsey\Uuid\Uuid;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => [
            'required',
            'confirmed',
            Rules\Password::defaults()
                ->min(8)
                ->letters()
                ->numbers()
                ->symbols()
                ->mixedCase()
                ],
            ], [
                'password.required' => 'A senha é obrigatória',
                'password.min' => 'A senha deve ter no mínimo 8 caracteres',
                'password.letters' => 'A senha deve conter letras',
                'password.numbers' => 'A senha deve conter números',
                'password.symbols' => 'A senha deve conter símbolos especiais (@$!%*#?&)',
                'password.mixedCase' => 'A senha deve conter letras maiúsculas e minúsculas'
        ]);

        $user = User::create([
            'profile_id'=> ProfileController::$USUARIO,
            'hash' => Uuid::uuid4(),
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
