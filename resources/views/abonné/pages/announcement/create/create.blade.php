@extends('abonné.include.layouts.ap')
@section('content')

<form id="createAdForm" action="{{ route('announcement.store') }}" method="POST" enctype="multipart/form-data" onsubmit="showLoading()">
    @csrf
    <h3 class="text-black-50 mb-4">Créer votre annonce</h3>


    <div class="card shadow-lg border-0 rounded-lg mb-4">
        <div class="card-header text-black">
            <h5>Photos de l'annonce</h5>
            <small class="text-muted">Vous pouvez ajouter jusqu'à {{ $maxPhotos }} photos au max.</small>
        </div>
        <div class="card-body d-flex flex-wrap gap-3">
            @for ($i = 0; $i < $maxPhotos; $i++)
                @php
                    $photoLocked = !$userHasSubscription && $i >= $freemiumPhotos;
                @endphp

                <div class="d-flex flex-column align-items-center position-relative">
                    <div
                        class="upload-icon bg-light d-flex align-items-center justify-content-center border rounded shadow-sm position-relative"
                        style="width: 100px; height: 100px; cursor: pointer;"
                        data-photo-index="{{ $i }}"
                        data-locked="{{ $photoLocked ? 'true' : 'false' }}"
                    >
                        <img id="preview_photo_{{ $i }}" alt="" class="img-thumbnail d-none" style="width: 100%; height: 100%; object-fit: cover;">
                        <i class="bi bi-image text-muted preview-icon" style="font-size: 30px;"></i>

                        @if($photoLocked)
                            <div class="position-absolute top-50 start-50 translate-middle bg-dark bg-opacity-75 text-white rounded-circle p-2">
                                <i class="bi bi-lock-fill"></i>
                            </div>
                        @endif

                        <button type="button" id="remove_photo_{{ $i }}" class="btn btn-danger btn-sm position-absolute top-0 end-0 d-none" style="padding: 4px;">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </div>

                    <input type="file" class="d-none" id="photo_{{ $i }}" name="photos[]" accept="image/*" data-photo-index="{{ $i }}">
                    <span class="text-muted mt-2" style="font-size: 14px;">
                        Photo {{ $i === 0 ? 'principale' : 'annexe ' . $i }}
                    </span>
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
            <small class="text-muted">
                Vous pouvez ajouter jusqu'à {{ $maxVideos }} vidéos au max.
            </small>
        </div>
        <div class="card-body d-flex flex-wrap gap-3">
            @for ($j = 0; $j < $maxVideos; $j++)
                @php
                    $videoLocked = !$userHasSubscription && $j >= $freemiumVideos;
                @endphp

                <div class="d-flex flex-column align-items-center position-relative">
                    <div
                        class="upload-icon bg-light d-flex align-items-center justify-content-center border rounded shadow-sm position-relative"
                        style="width: 150px; height: 100px; cursor: pointer;"
                        data-video-index="{{ $j }}"
                        data-video-locked="{{ $videoLocked ? 'true' : 'false' }}"
                    >
                        <video id="preview_video_{{ $j }}" class="d-none" style="width: 100%; height: 100%; object-fit: cover;" controls></video>
                        <i class="bi bi-camera-video text-muted preview-icon" style="font-size: 30px;"></i>

                        @if($videoLocked)
                            <div class="position-absolute top-50 start-50 translate-middle bg-dark bg-opacity-75 text-white rounded-circle p-2">
                                <i class="bi bi-lock-fill"></i>
                            </div>
                        @endif

                        <button type="button" id="remove_video_{{ $j }}" class="btn btn-danger btn-sm position-absolute top-0 end-0 d-none" style="padding: 4px;">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </div>
                    <input type="file" class="d-none" id="video_{{ $j }}" name="videos[]" accept="video/*" data-video-index="{{ $j }}">
                    <span class="text-muted mt-2" style="font-size: 14px;">
                        Vidéo {{ $j === 0 ? 'principale' : 'annexe ' . $j }}
                    </span>
                </div>
            @endfor
        </div>
        <div class="card-footer text-black">
            <small class="text-muted"> Formats de vidéos acceptés : <strong>MP4, AVI, MKV, FLV, MOV, WMV, WEBM</strong>. Taille max : <strong>2 Mo</strong>.
            </small>
        </div>
    </div>

    <div class="card shadow-lg border-0 rounded-lg mb-4">
        <div class="card-header text-black">
            <h5>Détails de l'annonce</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="title" class="form-label text-black">Titre<span class="text-danger" title="obligatoire pour publier votre annonce">*</span></label>
                <input type="text" class="form-control form-control-sm" id="title" name="titre" required placeholder="Titre de votre annonce">
            </div>
            <div class="mb-3">
                <label for="ad_type" class="form-label text-black">Type d'annonce<span class="text-danger" title="obligatoire pour publier votre annonce">*</span></label>
                <select class="form-select form-control form-select-sm" id="type_annonce" name="type_offre">
                    <option value="" disabled selected>Sélectionnez un type</option>
                    <option value="Location">Location</option>
                    <option value="Vente">Vente</option>
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
                        min="1" oninput="validateInput01()">
                    <span class="input-group-text">CFA</span>
                </div>
            </div>

            <div class="mb-3">
                <label for="location" class="form-label text-black">Lieu<span class="text-danger" title="obligatoire pour publier votre annonce">*</span></label>
                <input type="text" class="form-control form-control-sm" id="location" name="lieu" placeholder="Lieu où se trouve votre bien">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label text-black">Description<span class="text-danger" title="obligatoire pour publier votre annonce">*</span></label>
                <textarea class="form-control form-control-sm" id="description" name="description" rows="4" maxlength="200" placeholder="Une petite description de votre annonce"></textarea>
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
                <label for="categorySelect" class="form-label text-black">Catégorie<span class="text-danger" title="obligatoire">*</span></label>
                <select class="form-select form-control form-select-sm" id="categorySelect" name="category" required>
                    <option value="" disabled selected>Sélectionnez une catégorie</option>
                    @foreach($categories as $categorie)
                    <option value="{{ $categorie->id }}">{{ $categorie->titre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3" id="parametersContainer"></div>
            <div class="d-flex justify-content-center gap-3">
                <button type="submit" name="action" value="save" class="btn btn-primary px-3 py-2">
                    <i class="bi bi-save me-2"></i> Enregistrer
                </button>
                <button type="submit" name="action" value="publish" class="btn btn-primary btn btn-warning text-white px-3 py-2">
                    <i class="bi bi-megaphone me-2"></i> Publier
                </button>
            </div>
        </div>
    </div>
</form>

@include('abonné.pages.announcement.create.script') <!-- Pour les js à utiliser pour cette page -->
@include('abonné.pages.announcement.create.modal') <!-- Pour les fenetres modales pour cette page -->

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

    .locked-pv {
        opacity: 0.5;
        pointer-events: none;
    }

</style>

@endsection
