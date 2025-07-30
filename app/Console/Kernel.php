<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Models\Abonnement;
use App\Models\Boost;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

    // Fonction pour mettre à jour tous les abonnements et boosts expirés
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $abonnementsExpirés = Abonnement::where('date_fin', '<', now())
                ->where('statut', 'actif')
                ->update(['statut' => 'expiré']);

            $boostsExpirés = Boost::where('date_fin', '<', now())
                ->where('statut', 'actif')
                ->update(['statut' => 'expiré']);

            Log::info("Tâche CRON exécutée : $abonnementsExpirés abonnement(s) expiré(s), $boostsExpirés boost(s) expiré(s).");
        })
        ->everyMinute(); // Pour chaque minute
        //->dailyAt('00:00'); // Pour chaque un jour à minuit
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
