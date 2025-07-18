<div class="card shadow-lg border-0 rounded-4 overflow-hidden position-relative text-white" style="background: linear-gradient(135deg, #1e1e2f, #3c3c5c);">
    <!-- Bande décorative -->
    <div class="position-absolute top-0 start-0 w-100" style="height: 8px; background: linear-gradient(90deg, #ff416c, #ff4b2b); z-index: 1;"></div>

    <!-- Contenu principal -->
    <div class="card-body px-4 py-5 text-center position-relative" style="z-index: 2;">
        <div class="mb-4">
            <i class="bi bi-wallet2 fs-1 text-warning"></i>
        </div>
        <h4 class="fw-bold">Mon Portefeuille</h4>
        <p class="fs-3 mb-2">
            <span class="text-light">Solde :</span>
            <span class="fw-bold text-warning">
                {{ number_format(auth()->user()->portefeuilleActif->solde, 0, ',', ' ') }} FCFA
            </span>
        </p>
        <small class="text-muted">Dernière mise à jour : {{ now()->translatedFormat('d M Y à H:i') }}</small>

        <div class="mt-4">
            <button class="btn btn-outline-light rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#">
                <i class="bi bi-plus-circle me-1"></i> Recharger
            </button>
        </div>
    </div>
</div>
