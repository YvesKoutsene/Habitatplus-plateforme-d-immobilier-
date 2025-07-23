@extends('abonné.include.layouts.ap')
@section('content')

<form id="createAdForm" action="{{ route('announcement.update', $bien->id) }}" method="POST" enctype="multipart/form-data" onsubmit="showLoading()">
    @csrf
    @method('PUT')
    <h3 class="text-black-50 mb-4">Modifier votre annonce</h3>

    <div class="card shadow-lg border-0 rounded-lg mb-4">
        <div class="card-header text-black">
            <h5>Photos de l'annonce</h5>
            <small class="text-muted">Vous pouvez ajouter jusqu'à 7 photos : une principale et six annexes.</small>
        </div>
        <div class="card-body d-flex flex-wrap gap-3">
            @php
                $photos = $bien->photos;
                $maxPhotos = 7;
                $userHasSubscription = auth()->user()?->abonnementActif !== null;
            @endphp

            @for ($i = 0; $i < $maxPhotos; $i++)
                @php
                    $photo = $photos[$i] ?? null;
                    $photoSrc = $photo ? asset($photo->url_photo) : '';
                    $photoLocked = !$userHasSubscription && $i > 2;
                @endphp

                <div class="d-flex flex-column align-items-center position-relative">
                    <label for="photo_{{ $i }}"
                        class="upload-icon bg-light d-flex align-items-center justify-content-center border rounded shadow-sm position-relative {{ $photoLocked ? 'locked-pv' : '' }}"
                        style="width: 100px; height: 100px; cursor: {{ $photoLocked ? 'not-allowed' : 'pointer' }};"
                        data-bs-toggle="{{ $photoLocked ? 'modal' : '' }}"
                        data-bs-target="{{ $photoLocked ? '#upgradeModal' : '' }}">

                        <img id="preview_photo_{{ $i }}" src="{{ $photoSrc }}" alt="" class="img-thumbnail {{ $photo ? '' : 'd-none' }}"
                            style="width: 100%; height: 100%; object-fit: cover;">

                        <i class="bi bi-image text-muted preview-icon {{ $photo ? 'd-none' : '' }}" style="font-size: 30px;"></i>

                        @if ($photoLocked)
                            <div class="position-absolute top-50 start-50 translate-middle bg-dark bg-opacity-75 text-white rounded-circle p-2">
                                <i class="bi bi-lock-fill"></i>
                            </div>
                        @endif

                        @if (!$photoLocked || !$photo)
                            <button type="button" id="remove_photo_{{ $i }}" class="btn btn-danger btn-sm position-absolute top-0 end-0 {{ $photo ? '' : 'd-none' }}"
                                style="padding: 4px;"
                                data-photo-id="{{ $photo->id ?? '' }}">
                                <i class="bi bi-x-circle"></i>
                            </button>
                        @endif
                    </label>
                    <input type="file" class="d-none" id="photo_{{ $i }}" name="photos[{{ $i }}]" accept="image/*" {{ $photoLocked ? 'disabled' : '' }}>
                    @if ($photo)
                        <input type="hidden" name="existing_photos[]" value="{{ $photo->id }}">
                    @endif
                    <input type="hidden" name="deleted_photos[]" value="" id="deleted_photo_{{ $i }}">
                    <span class="text-muted mt-2" style="font-size: 14px;">Photo {{ $i === 0 ? 'principale' : 'annexe ' . $i }}</span>
                </div>
            @endfor
        </div>
        <div class="card-footer text-black">
            <small class="text-muted">Formats de photo acceptés : <strong>JPEG, PNG, JPG, GIF</strong>. Taille max : <strong>2 Mo</strong>.</small>
        </div>
    </div>

    <div class="card shadow-lg border-0 rounded-lg mb-4">
        <div class="card-header text-black">
            <h5>Vidéos de l'annonce</h5>
            <small class="text-muted">Vous pouvez ajouter jusqu'à 2 vidéos : une principale et une annexe.</small>
        </div>
        <div class="card-body d-flex flex-wrap gap-3">
            @php
                $videos = $bien->videos;
                $maxVideos = 2;
                $userHasSubscription = auth()->user()?->abonnementActif !== null;
            @endphp

            @for ($j = 0; $j < $maxVideos; $j++)
                @php
                    $video = $videos[$j] ?? null;
                    $videoSrc = $video ? asset($video->url_video) : '';
                    $videoLocked = !$userHasSubscription;
                @endphp

                <div class="d-flex flex-column align-items-center position-relative">
                    <label for="video_{{ $j }}"
                        class="upload-icon bg-light d-flex align-items-center justify-content-center border rounded shadow-sm position-relative {{ $videoLocked ? 'locked-pv' : '' }}"
                        style="width: 150px; height: 100px; cursor: {{ $videoLocked ? 'not-allowed' : 'pointer' }};"
                        data-bs-toggle="{{ $videoLocked ? 'modal' : '' }}"
                        data-bs-target="{{ $videoLocked ? '#videoUpgradeModal' : '' }}">

                        <video id="preview_video_{{ $j }}" src="{{ $videoSrc }}" alt="" class="img-thumbnail {{ $video ? '' : 'd-none' }}" style="width: 100%; height: 100%; object-fit: cover;" controls></video>
                        <i class="bi bi-camera-video text-muted preview-icon {{ $video ? 'd-none' : '' }}" style="font-size: 30px;"></i>

                        @if ($videoLocked)
                            <div class="position-absolute top-50 start-50 translate-middle bg-dark bg-opacity-75 text-white rounded-circle p-2">
                                <i class="bi bi-lock-fill"></i>
                            </div>
                        @endif

                        @if (!$videoLocked || !$video)
                            <button type="button" id="remove_video_{{ $j }}" class="btn btn-danger btn-sm position-absolute top-0 end-0 {{ $video ? '' : 'd-none' }}"
                                style="padding: 4px;"
                                data-video-id="{{ $video->id ?? '' }}">
                                <i class="bi bi-x-circle"></i>
                            </button>
                        @endif
                    </label>

                    <input type="file" class="d-none" id="video_{{ $j }}" name="videos[{{ $j }}]" accept="video/*" {{ $videoLocked ? 'disabled' : '' }}>
                    @if ($video)
                        <input type="hidden" name="existing_videos[]" value="{{ $video->id }}">
                    @endif
                    <input type="hidden" name="deleted_videos[]" value="" id="deleted_video_{{ $j }}">

                    {{-- Label du slot --}}
                    <span class="text-muted mt-2" style="font-size: 14px;">Vidéo {{ $j === 0 ? 'principale' : 'annexe ' . $j }}</span>
                </div>
            @endfor
        </div>
        <div class="card-footer text-black">
            <small class="text-muted">Formats de vidéo acceptés : <strong>MP4, WebM, MOV</strong>. Taille max : <strong>2 Mo</strong>.</small>
        </div>
    </div>

    <div class="card shadow-lg border-0 rounded-lg mb-4">
        <div class="card-header text-black">
            <h5>Détails de l'annonce</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="title" class="form-label text-black">Titre<span class="text-danger" title="obligatoire pour publier votre annonce">*</span></label>
                <input type="text" class="form-control form-control-sm" id="title" name="titre" required placeholder="Titre de votre annonce" value="{{ old('titre', $bien->titre) }}">
            </div>
            <div class="mb-3">
                <label for="ad_type" class="form-label text-black">
                    Type d'annonce
                    <span class="text-danger" title="obligatoire pour publier votre annonce">*</span>
                </label>
                <select class="form-select form-control form-select-sm" id="type_annonce" name="type_offre">
                    <option value="" disabled <?= empty($bien->type_offre) ? 'selected' : '' ?>>Sélectionnez un type</option>
                    <option value="Location" <?= $bien->type_offre === 'Location' ? 'selected' : '' ?>>Location</option>
                    <option value="Vente" <?= $bien->type_offre === 'Vente' ? 'selected' : '' ?>>Vente</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="prix" class="form-label text-black">Prix<span class="text-danger" title="obligatoire pour publier votre annonce">*</span></label>
                <div class="input-group mb-3">
                    <input
                        type="text"
                        name="prix"
                        id="prix_annonce"
                        class="form-control form-control-sm"
                        placeholder="Prix de votre annonce"
                        min="1" oninput="validateInput01()" value="{{ old('prix', $bien->prix !== null ? number_format($bien->prix, 0, ',', '') : '') }}">
                    <span class="input-group-text">CFA</span>
                </div>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label text-black">Lieu<span class="text-danger" title="obligatoire pour publier votre annonce">*</span></label>
                <input type="text" class="form-control form-control-sm" id="location" name="lieu" placeholder="Lieu où se trouve votre bien" value="{{ old('lieu', $bien->lieu) }}">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label text-black">Description<span class="text-danger" title="obligatoire pour publier votre annonce">*</span></label>
                <textarea class="form-control form-control-sm" id="description" name="description" rows="4" maxlength="200" placeholder="Une petite description de votre annonce">{{$bien->description}}</textarea>
                <small class="text-muted">Ne pas dépasser 200 caractères maximum.</small>
            </div>
        </div>
    </div>
    <div class="card shadow-lg border-0 rounded-lg mb-4">
        <div class="card-header text-black">
            <h5>Catégorie de bien</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="categorySelect" class="form-label text-black">
                    Catégorie<span class="text-danger" title="obligatoire">*</span>
                </label>
                <select class="form-select form-control form-select-sm" id="categorySelect" name="category" required>
                    <option value="" disabled>Sélectionnez une catégorie</option>
                    @foreach($categories as $categorie)
                    <option value="{{ $categorie->id }}" {{ $bien->categorieBien->id == $categorie->id ? 'selected' : '' }}>
                        {{ $categorie->titre }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3" id="parametersContainer"></div>
            <input type="hidden" name="current_category" value="{{ $bien->id_categorie_bien }}">

            @if($bien->statut == 'brouillon')
            <div class="d-flex justify-content-center gap-3">
                <button type="submit" name="action" value="save" class="btn btn-primary px-3 py-2">
                    <i class="bi bi-check-all me-2"></i> Enregistrer modification
                </button>
                <button type="submit" name="action" value="publish" class="btn btn-primary btn btn-warning text-white px-3 py-2">
                    <i class="bi bi-megaphone me-2"></i> Publier
                </button>
            </div>
            @elseif ($bien->statut == 'publié' || $bien->statut == 'republié')
            <div class="d-flex justify-content-center gap-3">
                <button type="submit" name="action" value="save" class="btn btn-primary px-3 py-2">
                    <i class="bi bi-check-all me-2"></i> Enregistrer modification
                </button>
            </div>
            @elseif ($bien->statut == 'terminé')
            <div class="d-flex justify-content-center gap-3">
                <button type="submit" name="action" value="save" class="btn btn-primary px-3 py-2">
                    <i class="bi bi-check-all me-2"></i> Enregistrer modification
                </button>
                <button type="submit" name="action" value="publish" class="btn btn-primary btn btn-warning text-white px-3 py-2">
                    <i class="bi bi-megaphone me-2"></i> Republier
                </button>
            </div>
            @endif
        </div>
    </div>
</form>

@include('abonné.pages.announcement.edit.script') <!-- Pour les js à utiliser pour cette page -->
@include('abonné.pages.announcement.edit.modal') <!-- Pour les fenetres modales pour cette page -->


<style>
    .photo-wrapper {
        position: relative;
        border: 1px solid #ddd;
        border-radius: 5px;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .photo-wrapper:hover {
        transform: scale(1.05);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .photo-wrapper img {
        display: block;
    }

    .photo-wrapper button {
        background-color: rgba(255, 255, 255, 0.8);
        border: none;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .photo-wrapper button:hover {
        background-color: rgba(255, 0, 0, 0.8);
        color: white;
    }

    .upload-icon {
        transition: background-color 0.2s ease-in-out;
    }

    .upload-icon:hover {
        background-color: #f8f9fa;
    }

    .is-invalid {
        border-color: red;
        background-color: #f8d7da;
    }

    .yes{
        background-color: #007bff;
    }

</style>

@endsection
