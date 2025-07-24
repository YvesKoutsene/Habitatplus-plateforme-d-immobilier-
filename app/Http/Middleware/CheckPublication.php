<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ModeleAbonnement;
use App\Models\ParametreModele;
use App\Models\User;

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

    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect('/')->with('error', 'Veuillez vous connecter pour publier une annonce.');
        }

        // Cas 1 : Abonnement actif → pas de limite
        if ($user->abonnementActif && !$user->abonnementActif->isExpired()) {
            return $next($request);
        }

        // Cas 2 : Freemium (non abonné ou abonnement expiré)
        $freemium = ModeleAbonnement::where(function ($query) {
            $query->whereRaw('LOWER(nom) = ?', ['freemium'])
                  ->orWhere('prix', 0);
        })->first();

        $quota = $freemium?->getValeurParametre('Annonces/mois') ?? 3; // Par défaut 3

        // Déterminer la date de départ de la période Freemium
        $lastAbonnementExpire = $user->abonnements()
            ->where('date_fin', '<', now())
            ->orderByDesc('date_fin')
            ->first();

        $start = $lastAbonnementExpire
            ? $lastAbonnementExpire->date_fin->copy()->addDay()->startOfDay()
            : $user->created_at->copy()->startOfDay();
            //: $user->email_verified_at->copy()->startOfDay();

        // Calculer la période mensuelle actuelle
        $diffInMonths = $start->diffInMonths(now());
        $debutPeriode = $start->copy()->addMonths($diffInMonths);
        $finPeriode = $debutPeriode->copy()->addMonth()->subSecond();

        // Vérifier le nombre de publications durant cette période
        $annoncesPubliees = $user->biens()
            ->whereBetween('datePublication', [$debutPeriode, $finPeriode])
            ->count();

        if ($annoncesPubliees >= $quota) {
            return redirect()->back()
                //->with('error', "Vous avez atteint la limite de $quota annonce(s) gratuite(s) pour la période du {$debutPeriode->format('d/m/Y')} au {$finPeriode->format('d/m/Y')}.")
                ->with('error', "Vous avez atteint la limite de $quota annonce(s) gratuite(s) pour ce mois-ci")
                ->with('limit_error', true)
                ->with('freemium_quota', $quota)
                ->with('freemium_start', $debutPeriode->format('d/m/Y'))
                ->with('freemium_end', $finPeriode->format('d/m/Y'));
        }

        return $next($request);
    }

}
