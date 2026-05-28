<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    // ── Login ──────────────────────────────────────────────────────────────

    public function mostrarLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        // Validação estrutural mínima (campos presentes e formato básico)
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'O e-mail é obrigatório.',
            'email.email'       => 'Informe um e-mail válido.',
            'password.required' => 'A senha é obrigatória.',
        ]);

        $credenciais = $request->only('email', 'password');
        $lembrar     = $request->boolean('remember');

        if (Auth::attempt($credenciais, $lembrar)) {
            // Regenerar a sessão previne fixação de sessão
            $request->session()->regenerate();

            // intended() redireciona ao destino original caso o usuário tenha
            // sido interceptado pelo middleware auth; caso contrário, vai ao dashboard
            return redirect()->intended(route('dashboard'));
        }

        // Mensagem genérica — não revela se o e-mail existe ou não
        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Credenciais inválidas. Verifique e-mail e senha.']);
    }

    // ── Registro ───────────────────────────────────────────────────────────

    public function mostrarRegistro(): View
    {
        return view('auth.register');
    }

    public function registrar(RegisterRequest $request): RedirectResponse
    {
        // validated() retorna apenas os campos aprovados pelo RegisterRequest
        // O cast 'hashed' no model garante que a senha seja armazenada com bcrypt
        $usuario = User::create($request->validated());

        // Autenticar imediatamente após o cadastro
        Auth::login($usuario);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    // ── Logout ─────────────────────────────────────────────────────────────

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        // Invalidar a sessão e gerar novo token CSRF previnem ataques de
        // reutilização de sessão e CSRF pós-logout
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
