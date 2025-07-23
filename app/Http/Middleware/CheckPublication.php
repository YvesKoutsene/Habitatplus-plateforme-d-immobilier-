<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

class CheckPublication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    /*public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }*/

    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour publier une annonce.');
        }

        // Cas 1 : S'il a un abonnement actif NON expiré
        if ($user->abonnementActif && !$user->abonnementActif->isExpired()) {
            return $next($request);
        }

        // Cas 2 : Non abonné ou abonnement expiré => vérifier le quota de 3 annonces/mois
        $debutDuMois = now()->startOfMonth();
        $finDuMois = now()->endOfMonth();

        $annoncesPublieesCeMois = $user->biens()
            //->whereBetween('created_at', [$debutDuMois, $finDuMois])
            ->whereBetween('datePublication', [$debutDuMois, $finDuMois])
            ->count();

        if ($annoncesPublieesCeMois >= 3) {
            return redirect()->back()->with('error', 'Vous avez épuisé vos 3 publications gratuites de ce mois-ci.')
                   ->with('limit_error', true);;
        }

        return $next($request);
    }

}
