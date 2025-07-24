<?php

namespace App\Http\Controllers\Estate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Abonnement;
use App\Models\ModeleAbonnement;
use App\Models\Portefeuille;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    // Pour renvoyer les abonnements actifs
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('perPage', 10);

        $query = Abonnement::with(['user', 'modele'])
            ->where('statut', 'actif')
            ->orderBy('date_début', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($subQuery) use ($search) {
                    $subQuery->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%'])
                             ->orWhereRaw('LOWER(email) LIKE ?', ['%' . strtolower($search) . '%']);
                })->orWhereHas('modele', function ($subQuery) use ($search) {
                    $subQuery->whereRaw('LOWER(nom) LIKE ?', ['%' . strtolower($search) . '%']);
                });
            });
        }

        $abonnements = $query->paginate($perPage);

        return view('admin.pages.subscription.index', compact('abonnements', 'search', 'perPage'));
    }

    // Pour renvoyer les abonnements expirés
    public function index02(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('perPage', 10);

        $query = Abonnement::with(['user', 'modele'])
            ->where('statut', 'expiré')
            ->orderBy('date_début', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($subQuery) use ($search) {
                    $subQuery->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%'])
                             ->orWhereRaw('LOWER(email) LIKE ?', ['%' . strtolower($search) . '%']);
                })->orWhereHas('modele', function ($subQuery) use ($search) {
                    $subQuery->whereRaw('LOWER(nom) LIKE ?', ['%' . strtolower($search) . '%']);
                });
            });
        }

        $abonnements = $query->paginate($perPage);

        return view('admin.pages.subscription.index02', compact('abonnements', 'search', 'perPage'));
    }

    public function create()
    {
        //
    }

    // Pour enrégistrer un abonnement d'un user par portefeuille
    public function store(Request $request)
    {
        $request->validate([
            'duree' => 'required|integer|min:1|max:12',
            'modele_id' => 'required|exists:modele_abonnements,id',
            'mode' => 'required|in:wallet,mobile',
        ]);

        //dd($request->all());

        // Paiement direct est à venir après
        if ($request->mode !== 'wallet') {
            return back()->with('info', 'Le paiement Mobile Money n’est pas encore disponible.');
        }

        $user = auth()->user();
        $portefeuille = $user->portefeuilleActif;

        if (!$portefeuille) {
            return back()->with('error', 'Portefeuille introuvable.');
        }

        $modele = ModeleAbonnement::find($request->modele_id);

        $duree = $request->duree;
        $montant = ($duree === 12) ? 25000 : $modele->prix * $duree;

        if ($portefeuille->solde < $montant) {
            return back()->with('error', 'Votre solde est insuffisant pour cette opération.');
        }

        DB::beginTransaction();

        try {
            // Expirer l’ancien abonnement actif s’il existe
                # A revoir ici après par Moi
            $user->abonnementActif()?->update(['statut' => 'expiré']);

            // Création du nouvel abonnement
            Abonnement::create([
                'keyabonnement' => Str::uuid()->toString(),
                'duree' => $duree,
                'montant' => $montant,
                'id_user' => $user->id,
                'modele_id' => $modele->id,
                'date_début' => now(),
                'date_fin' => now()->addMonths($duree),
                'statut' => 'actif',
                'createdby' => $user->id,
            ]);

            // Débiter le portefeuille
            $portefeuille->decrement('solde', $montant);

            DB::commit();
            return back()->with('success', 'Abonnement activé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la souscription : ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
