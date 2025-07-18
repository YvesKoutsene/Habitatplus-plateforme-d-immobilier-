<div class="profile-card container my-5">
    <!-- En-tête profil -->
    <div class="profile-header position-relative text-center text-white p-5 rounded-top">
        <img src="{{ asset(Auth::user()->photo_profil) }}"
             alt="Photo de profil"
             class="profile-avatar shadow-lg">

        <h3 class="mt-4 mb-1">{{ auth()->user()->name }}</h3>
        <p class="mb-1">{{ auth()->user()->email }}</p>

        @php
            \Carbon\Carbon::setLocale('fr');
            $diff = \Carbon\Carbon::parse(Auth::user()->created_at)->diffForHumans(null, true);
        @endphp

        <p class="text-light opacity-75 mb-0">Actif depuis {{ $diff }}</p>
    </div>

    <!-- Corps -->
    <div class="card-body bg-white p-4 rounded-bottom shadow-sm">
        <!-- Parrainage -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            @php
                $code = auth()->user()->parrainage?->code;
            @endphp

            <div>
                <span class="fw-bold me-2 text-black">Code de parrainage :</span>
                <span class="badge bg-success">{{ $code ?? 'Aucun' }}</span>
            </div>

            @if($code)
                <button class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard('{{ $code }}')">
                    <i class="bi bi-clipboard"></i> Copier
                </button>
            @endif
        </div>

        <!-- Portefeuille -->
        <div class="wallet-card shadow-lg p-4 mb-4 border-0 rounded-4 overflow-hidden position-relative text-white" style="background: linear-gradient(135deg, #1e1e2f, #3c3c5c);">
            <div class="position-absolute top-0 start-0 w-100" style="height: 8px; background: linear-gradient(90deg, #ff416c, #ff4b2b); z-index: 1;"></div>
            <div class="position-absolute opacity-10" style="top: 10px; right: 20px; font-size: 5rem;">
                <i class="bi bi-wallet2 text-warning"></i>
            </div>
            <h5 class="mb-1">Mon portefeuille</h5>
            <h3 class="fw-bold text-warning">{{ number_format(auth()->user()->portefeuilleActif->solde ?? 0, 0, ',', ' ') }} FCFA</h3>
            <button class="btn btn-outline-light btn-sm mt-3 rounded-pill" data-bs-toggle="modal" data-bs-target="#modal">
                <i class="bi bi-plus-circle me-1"></i> Recharger
            </button>
        </div>

        <!-- Boutons d'action -->
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <button class="btn btn-primary rounded-pill px-4 shadow" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                <i class="bi bi-pencil-square me-2"></i> Modifier profil
            </button>

            <button class="btn btn-warning rounded-pill px-4 shadow" data-bs-toggle="modal" data-bs-target="#editPasswordModal">
                <i class="bi bi-lock me-2"></i> Changer mot de passe
            </button>
        </div>
    </div>
</div>

<style>
    .profile-header {
        background: linear-gradient(135deg, #0d6efd, #6610f2);
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border: 4px solid #fff;
        border-radius: 50%;
        position: absolute;
        top: -60px;
        left: 50%;
        transform: translateX(-50%);
        background-color: white;
    }

    .wallet-card {
        background: linear-gradient(135deg, #1f1f1f, #3c3c3c);
        border-radius: 1rem;
    }

    /* Add by Jyl */
    .btn {
        border-radius: 25px;
        font-size: 1rem;
        font-weight: 600;
        padding: 12px 20px;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        transform: translateY(-3px);
    }

    .btn-danger:hover {
        background-color: rgba(179, 0, 0, 0.96);
        transform: translateY(-3px);
    }

    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
    }

    .btn-warning:hover {
        background-color: #e0a800;
        transform: translateY(-3px);
    }

    .btn-outline-info:hover {
        background-color: gray;
        transform: translateY(-3px);
    }

    .btn-secondary:hover {
        background-color: rgba(76, 91, 20, 0.66);
        transform: translateY(-3px);

</style>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function () {
        alert('Code copié : ' + text);
    }, function (err) {
        alert('Erreur lors de la copie');
    });
}
</script>

