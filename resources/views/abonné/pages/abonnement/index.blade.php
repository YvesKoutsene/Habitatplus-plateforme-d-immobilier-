$@php
     \Carbon\Carbon::setLocale('fr');
@endphp
<div>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach($modeles as $modele)
            <div class="col-12 col-sm-6 col-lg-6 position-relative">
                @if($modele->nom === 'Pro')
                    <div class="position-absolute top-0 end-0 bg-warning text-dark px-3 py-1 rounded-start shadow-sm" style="z-index: 10;">
                        ⭐ Recommandé
                    </div>
                @endif

                <div class="card h-100 border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-header bg-gradient text-white text-center py-4
                        {{ $modele->nom === 'Freemium' ? 'bg-success' : 'bg-danger' }}">
                        <h4 class="mb-0 fw-bold">{{ $modele->nom ?? 'Non spécifié' }}</h4>
                        @if($modele->nom === 'Freemium')
                            <small>(Limité)</small>
                        @else
                            @if( auth()->user()->abonnementActif )
                                <small>( {{ auth()->user()->abonnementActif->duree ?? 0 }} mois)</small>
                            @else
                                <small>(Par mois)</small>
                            @endif
                        @endif
                    </div>

                    <div class="card-body text-center p-4">
                        @if($modele->nom === 'Freemium' || $modele->prix === 0)
                            <h2 class="text-success fw-bold mb-3">Gratuit</h2>
                        @else
                            <h2 class="text-danger fw-bold mb-3">
                                {{ number_format($modele->prix ?? 0, 0, ',', ' ') }} FCFA<small class="text-muted">/Mois</small>
                            </h2>
                        @endif
                        <hr class="my-3">
                        <ul class="list-unstyled text-start">
                            @foreach($tousParametres as $parametre)
                                @php
                                    $association = $modele->parametres->firstWhere('id', $parametre->id);
                                @endphp

                                @if($association)
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <strong>
                                            {{ $association->pivot->valeur === 999 ? 'Annonces illimitées' : $association->pivot->valeur . ' ' . $parametre->nom_parametre }}
                                        </strong>
                                    </li>
                                @else
                                    <li class="mb-2">
                                        <i class="bi bi-x-circle-fill text-danger me-2"></i>
                                        <strong>{{ $parametre->nom_parametre }}</strong>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    <div class="card-footer bg-transparent text-center pb-4">
                        @if($modele->nom === 'Freemium')
                            @if( auth()->user()->abonnementActif )
                                <button class="btn btn-outline-primary" disabled><i class="bi bi-pause-circle-fill me-2"></i>Pause</button>
                            @else
                                <button class="btn btn-outline-primary" disabled><i class="bi bi-check-circle-fill me-2"></i>Activé par defaut</button>
                            @endif
                        @else
                            @if( auth()->user()->abonnementActif )
                                <span class="text-muted small me-2">
                                    Expire le <strong>{{ \Carbon\Carbon::parse(auth()->user()->abonnementActif->date_fin)->translatedFormat('d F Y') }}</strong>
                                </span>
                                <a href="#" class="btn btn-outline-danger rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#abonnementProModal"
                                   data-modele-id="{{ $modele->id }}" data-prix="{{ $modele->prix }}" data-renew="true">
                                    <i class="bi bi-arrow-repeat me-1"></i> Se réabonner
                                </a>
                            @else
                                <button class="btn btn-outline-danger rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#abonnementProModal"
                                        data-modele-id="{{ $modele->id }}" data-prix="{{ $modele->prix }}">
                                    <i class="bi bi-rocket-takeoff me-2"></i> Passer à {{ $modele->nom }}
                                </button>
                            @endif
                        @endif
                    </div>

                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Modale pour abonnement Pro et son réabonnement -->
<div class="modal fade" id="abonnementProModal" tabindex="-1" aria-labelledby="abonnementProLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <form id="subscriptionForm" action="{{ route('subscription.store') }}" method="POST" onsubmit="showLoading()">
                @csrf
                <input type="hidden" name="modele_id" id="modeleIdInput" value="{{ $modele->id }}">
                <input type="hidden" name="duree" id="hiddenDuree">
                <input type="hidden" name="mode" id="hiddenMode">

                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="abonnementProLabel">
                        <i class="bi bi-rocket-takeoff me-2"></i> Souscrire à l'abonnement Pro
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>

                <div class="modal-body text-black">
                    <div id="etape1">
                        <label for="dureeInput" class="form-label">Entrez la durée (en mois) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control form-control-sm" id="dureeInput" oninput="validateInputDuree()" required>
                        <!--
                        <span class="text-muted text-info">Obtenez une reduction sur 12 mois</span>
                        -->
                        <button type="button" class="btn btn-primary w-100 mt-3 px-3 py-2" id="btnAfficherFacture">Payer</button>
                    </div>

                    <div id="etape2" class="d-none">
                        <h6 class="text-center mb-3">Récapitulatif</h6>
                        <ul class="list-group mb-3">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Prix unitaire</span>
                                <span id="prixUnitaireText">-- FCFA</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Durée</span>
                                <span id="factureDuree">-- mois</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between fw-bold">
                                <span>Total</span>
                                <span id="factureTotal">-- FCFA</span>
                            </li>
                            <li class="list-group-item text-success d-none" id="remiseInfo">
                                🎉 Remise appliquée pour 12 mois : 25000 FCFA !
                            </li>
                        </ul>
                        <button type="button" class="btn btn-success w-100" id="btnChoisirPaiement">Choisir le mode de paiement</button>
                    </div>

                    <div id="etape3" class="d-none text-center">
                        <p class="text-muted mb-2">Choisissez votre mode de paiement :</p>
                        <div class="d-flex flex-wrap justify-content-center gap-3 mb-3">
                            <button type="button" id="btnWallet" class="btn btn-outline-primary w-45" onclick="choisirMode('wallet')">
                                <i class="bi bi-wallet2 me-2"></i>Portefeuille
                            </button>
                            <button type="button" id="btnMobile" class="btn btn-outline-success w-45" onclick="choisirMode('mobile')">
                                <i class="bi bi-phone me-2"></i>Mobile Money
                            </button>
                        </div>
                        <button type="button" class="btn btn-dark w-100" id="btnConfirmerFinal">Confirmer</button>
                    </div>

                    <div id="etape4" class="d-none text-center">
                        <div class="alert alert-info mb-3">
                            Êtes-vous sûr de vouloir souscrire pour <strong><span id="confirmDuree">--</span> mois</strong> ?<br>
                            Montant total : <strong><span id="confirmTotal">--</span> FCFA</strong><br>
                            Paiement via : <strong><span id="confirmMode">--</span></strong>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Valider et payer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('abonné.pages.abonnement.script') <!-- Pour le js à utiliser pour cette page -->
