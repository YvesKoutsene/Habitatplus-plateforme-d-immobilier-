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
                                <a href="#" class="btn btn-outline-danger rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#abonnementProModal">
                                    <i class="bi bi-arrow-repeat me-1"></i> Se réabonner
                                </a>
                            @else
                                <button class="btn btn-outline-danger rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#abonnementProModal"
                                        data-modele-id="{{ $modele->id }}"
                                        data-prix="{{ $modele->prix }}">
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

<!-- Modale pour faire un abonnement Pro -->
<div class="modal fade" id="abonnementProModal" tabindex="-1" aria-labelledby="abonnementProLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <form action="{{ route('subscription.store') }}" method="POST" onsubmit="showLoading()">
                @csrf
                <input type="hidden" name="modele_id" value="{{ $modele->id }}">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="abonnementProLabel"><i class="bi bi-rocket-takeoff me-2"></i>Souscrire à l'abonnement Pro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body text-black">
                    <div class="mb-3">
                        <label for="duree" class="form-label">Durée de l’abonnement<span class="text-danger" title="Obligatoire">*</span></label>
                        <select class="form-select form-control form-select-sm" id="duree" name="duree" required>
                            <option value="" disabled selected>Selectionnez votre durée</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}">{{ $i }} mois</option>
                            @endfor
                        </select>
                    </div>
                    <p class="mt-2 text-muted">Total à payer : <strong id="totalPayer">-- FCFA</strong></p>
                </div>
                <div class="modal-footer flex-column text-center">
                    <p class="text-muted mb-2">Procéder au paiement via</p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <button type="submit" name="mode" value="wallet" class="btn btn-outline-primary">
                            <i class="bi bi-wallet2 me-2"></i>Portefeuille
                        </button>
                        <span class="align-self-center text-muted">ou</span>
                        <button type="submit" name="mode" value="mobile" class="btn btn-outline-success">
                            <i class="bi bi-phone me-2"></i>Mobile Money
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('abonnementProModal');
    modal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const modeleId = button.getAttribute('data-modele-id');
        const prix = parseInt(button.getAttribute('data-prix'));

        const inputHidden = modal.querySelector('input[name="modele_id"]');
        inputHidden.value = modeleId;

        const dureeSelect = modal.querySelector('#duree');
        const totalPayer = modal.querySelector('#totalPayer');

        dureeSelect.onchange = () => {
            const duree = parseInt(dureeSelect.value);
            let total = (duree === 12) ? 25000 : prix * duree;
            if (totalPayer) {
                totalPayer.textContent = total.toLocaleString('fr-FR') + ' FCFA';
            }
        };
    });
</script>

<!-- Modale pour refaire un abonnement Pro -->

