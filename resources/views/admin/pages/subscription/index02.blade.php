@extends('admin.include.layouts.app')

@section('content')

@php
    \Carbon\Carbon::setLocale('fr');
@endphp

<div class="pagetitle">
    <h1>Abonnements</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li>
            <li class="breadcrumb-item">Abonnements</li>
            <li class="breadcrumb-item active">Abonnements Expirés</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Liste des Abonnements Expirés</h5>
                    </div>
                    @if(!$abonnements->isEmpty())
                    <div class="d-flex mb-3 justify-content-between">
                        <form action="{{ route('subscription.list02') }}" method="GET" class="d-flex">
                            <select name="perPage" class="form-select me-2" onchange="this.form.submit()">
                                <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5 entrées/page</option>
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 entrées/page</option>
                                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 entrées/page</option>
                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 entrées/page</option>
                            </select>
                        </form>
                        <form action="{{ route('subscription.list02') }}" method="GET" class="d-flex">
                            <input type="text" name="search" class="form-control me-2" placeholder="Taper ici pour chercher..." value="{{ request()->get('search') }}">
                            <button type="submit" class="btn btn-primary" title="Rechercher"><i class="bi bi-search"></i></button>
                        </form>
                    </div>
                    @endif

                    @if($abonnements->isEmpty())
                    <div class="alert alert-info">
                        Aucun abonnement actif pour le moment.
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr>
                                <th>Propriétaire</th>
                                <th>Modele d'abonnement</th>
                                <th>Prix (FCFA)</th>
                                <th>Duree</th>
                                <th>Date Début</th>
                                <th>Date Fin</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($abonnements as $abonnement)
                            <tr>
                                <td>
                                    <img src="{{ asset($abonnement->user->photo_profil) }}" alt="Profil" class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover;">
                                    | {{ $abonnement->user->name }}
                                </td>
                                <td>{{ $abonnement->modele->nom }}</td>
                                <td>{{ number_format($abonnement->montant, 0, ',', ' ') }}</td>
                                <td>{{ $abonnement->duree}} Mois</td>
                                <td>{{ \Carbon\Carbon::parse($abonnement->date_début)->translatedFormat('d F Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($abonnement->date_fin)->translatedFormat('d F Y') }}</td>
                                <td>
                                    <div class="d-flex">
                                        <a href="" class="btn btn-sm btn-outline-info me-2" title="Détails de l'abonnement">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Propriétaire</th>
                                <th>Modele d'abonnement</th>
                                <th>Prix (FCFA)</th>
                                <th>Duree</th>
                                <th>Date Début</th>
                                <th>Date Fin</th>
                                <th>Actions</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <nav aria-label="...">
                        <ul class="pagination justify-content-end">
                            <li class="page-item {{ $abonnements->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $abonnements->previousPageUrl() }}" tabindex="-1" aria-disabled="{{ $abonnements->onFirstPage() }}">Précédent</a>
                            </li>

                            @for ($i = 1; $i <= $abonnements->lastPage(); $i++)
                            <li class="page-item {{ $i == $abonnements->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $abonnements->url($i) }}">{{ $i }}</a>
                            </li>
                            @endfor
                            <li class="page-item {{ $abonnements->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ $abonnements->nextPageUrl() }}">Suivant</a>
                            </li>
                        </ul>
                    </nav>
                    @endif
                </div>
            </div>
        </div>
    </div>


</section>

@endsection
