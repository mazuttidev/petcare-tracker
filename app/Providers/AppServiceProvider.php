<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Limite de tentativas de login: 5 por minuto por e-mail + IP.
        // Usar email|IP como chave é mais preciso que só IP:
        //   • por e-mail → impede brute-force em uma conta específica
        //   • por IP     → impede varredura de muitas contas do mesmo endereço
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)
                ->by($request->input('email') . '|' . $request->ip())
                ->response(function () {
                    return back()
                        ->withInput()
                        ->withErrors(['email' => 'Muitas tentativas. Aguarde 1 minuto e tente novamente.']);
                });
        });
    }
}
