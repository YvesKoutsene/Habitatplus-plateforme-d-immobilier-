<div class="card shadow-lg border-0 rounded-lg overflow-hidden">
    <div class="text-white text-center py-5 position-relative">
        <!-- Élément de fond assombri -->
        <div class="bg-black" style="opacity: 0.6; position: absolute; inset: 0;"></div>

        <div class="profile-picture mb-4 position-relative">
            <img src="{{ asset(Auth::user()->photo_profil) }}"
                 alt="Photo de profil"
                 class="img-thumbnail rounded-circle shadow"
                 style="width: 120px; height: 120px; border: 2px solid #fff; position: relative; z-index: 1;">
        </div>

        <!-- Nom -->
        <h4 class="mb-0 text-light position-relative" style="z-index: 1;">
            {{ auth()->user()->name }}
        </h4>

        <!-- Code parrainage -->
        <div class="mt-2 mb-2 position-relative" style="z-index: 1;">
            @php
                $code = auth()->user()->parrainage?->code ?? null;
            @endphp

            @if ($code)
                <span class="badge bg-success px-3 py-2 shadow-sm" style="font-size: 0.9rem;">
                    Code de parrainage : {{ $code }}
                </span>
            @else
                <span class="badge bg-secondary px-3 py-2 shadow-sm" style="font-size: 0.9rem;">
                    Aucun code de parrainage
                </span>
            @endif
        </div>

        <!-- Email -->
        <!--
        <p class="mb-1 text-light position-relative" style="z-index: 1;">
            {{ auth()->user()->email }}
        </p>
        -->

        <!-- Ancienneté -->
        @php
            $createdAt = \Carbon\Carbon::parse(Auth::user()->created_at);
            $now = \Carbon\Carbon::now();
            $diffInDays = $createdAt->diffInDays($now);
            $diffInMonths = $createdAt->diffInMonths($now);
            $diffInYears = $createdAt->diffInYears($now);
            $diffInHours = $createdAt->diffInHours($now);
            $diffInMins = $createdAt->diffInMinutes($now);
        @endphp

        <p class="small text-light position-relative mb-0" style="z-index: 1;">
            Actif depuis :
            @if ($diffInYears > 0)
                <strong>{{ $diffInYears }} an{{ $diffInYears > 1 ? 's' : '' }}</strong>
            @elseif ($diffInMonths > 0)
                <strong>{{ $diffInMonths }} mois</strong>
            @elseif ($diffInDays > 7)
                <strong>{{ floor($diffInDays / 7) }} semaine{{ floor($diffInDays / 7) > 1 ? 's' : '' }}</strong>
            @elseif ($diffInDays > 0)
                <strong>{{ $diffInDays }} jour{{ $diffInDays > 1 ? 's' : '' }}</strong>
            @elseif($diffInHours > 0)
                <strong>{{ $diffInHours }} heure{{ $diffInHours > 1 ? 's' : '' }}</strong>
            @else
                <strong>{{ $diffInMins }} minute{{ $diffInMins > 1 ? 's' : '' }}</strong>
            @endif
        </p>
    </div>

    <!-- Boutons -->
    <div class="card-body text-center">
        <div class="row justify-content-center">
            <div class="col-auto mb-3">
                <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    <i class="bi bi-pencil-square me-2"></i> Modifier informations
                </button>
            </div>
            <div class="col-auto mb-3">
                <button class="btn btn-warning shadow-sm" data-bs-toggle="modal" data-bs-target="#editPasswordModal">
                    <i class="bi bi-lock me-2"></i> Changer mot de passe
                </button>
            </div>
        </div>
    </div>
</div>


<style>
    .card {
        margin: 30px auto;
        border-radius: 15px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: scale(1.02);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
    }

    .profile-picture img {
        transition: transform 0.3s ease;
        border-radius: 50%;
    }

    .profile-picture img:hover {
        transform: scale(1.3);
    }

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
    }

    .text-light {
        opacity: 0.9;
    }

    .text-light strong {
        font-weight: bold;
    }
</style>
