<!-- Fenetre pour les erreurs -->
<div class="modal fade" id="validationModal" tabindex="-1" aria-labelledby="validationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header yes">
                <h5 class="modal-title" id="validationModalLabel">Erreur de Validation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-black" id="validationMessage">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i>
                    Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Fenetre pour passer à Pro (Photo) -->
<div class="modal fade" id="upgradeModal" tabindex="-1" aria-labelledby="upgradeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow border-0">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="upgradeModalLabel"><i class="bi bi-lock-fill me-2"></i>Fonctionnalité réservée</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body text-black">
                <p>Pour ajouter plus de <strong>{{ $freemiumPhotos }}</strong> photos à vos annonces, vous devez passer à un abonnement <strong>Pro</strong>.
                </p>
                <p>Avec l’abonnement <strong>Pro</strong>, vous pouvez ajouter jusqu’à <strong>{{ $maxPhotos }}</strong> photos par annonce.</p>
            </div>
            <div class="modal-footer">
                <a href="{{ route('dashboard', ['onglet' => 'abonnements']) }}" class="btn btn-danger">
                    <i class="bi bi-rocket"></i> Passer à Pro
                </a>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Fenetre pour passer à Pro (Video) -->
<div class="modal fade" id="videoUpgradeModal" tabindex="-1" aria-labelledby="videoUpgradeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="videoUpgradeModalLabel"><i class="bi bi-lock-fill me-2"></i>Fonctionnalité réservée</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body text-black">
                <p>Les vidéos sont réservées aux membres <strong>Pro</strong>.</p>
                <p>Avec cet abonnement, vous pouvez ajouter jusqu’à <strong>{{ $maxVideos }}</strong> vidéos par annonce pour attirer plus d'attention.</p>
            </div>
            <div class="modal-footer">
                <a href="{{ route('dashboard', ['onglet' => 'abonnements']) }}" class="btn btn-danger">
                    <i class="bi bi-rocket"></i> Passer à Pro
                </a>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
