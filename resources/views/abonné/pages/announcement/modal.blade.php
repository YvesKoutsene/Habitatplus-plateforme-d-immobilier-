<!-- Modal pour arrêter une annonce -->
<div class="modal fade" id="terminateConfirmation{{ $bien->id }}" tabindex="-1" aria-labelledby="terminateConfirmationLabel{{ $bien->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header yes">
                <i class="bi bi-exclamation-triangle me-1"></i>
                <h5 class="modal-title" id="terminateConfirmationLabel{{ $bien->id }}">Confirmation d'annulation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-black">
                Êtes-vous sûr de vouloir arrêter cette annonce ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Annuler</button>
                <form action="{{ route('announcement.terminate', $bien->id) }}" method="POST" class="d-inline" onsubmit="showLoading()">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-danger"><i class="bi bi-check"></i> Valider</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour booster une annonce -->
<div class="modal fade" id="boostAnnonce{{ $bien->id }}" tabindex="-1" aria-labelledby="boostAnnonceLabel{{ $bien->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <i class="fas fa-bolt me-1 text-black"></i>
                <h5 class="modal-title text-black" id="boostAnnonceLabel{{ $bien->id }}">Booster votre annonce</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('announcement.boost', $bien->id) }}" method="POST" class="d-inline" onsubmit="showLoading()">
                @csrf
                @method('POST')
                <div class="modal-body ">
                    <div class="mb-3">
                        <label for="boost_type" class="form-label text-black">Type de Boost<span class="text-danger" title="obligatoire">*</span></label>
                        <select id="boost_type" class="form-select form-control form-select-sm" name="type_boost" required>
                            <!--<option value="" disabled selected>Sélectionnez un type</option>-->
                            <option value="top">Top (Annonce affichée en haut)</option>
                            <!--<option value="mise_en_avant">Highlight (Encadrement spécial)</option>
                                                    <option value="auto-remontee">Auto-remontée (Remontée automatique)</option>-->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="duree" class="form-label text-black">Durée<span class="text-danger" title="obligatoire">*</span></label>
                        <div class="row">
                            <div class="col-5 col-md-5">
                                <input type="text" id="duree" min="1" name="duree" class="form-control form-select-sm" required placeholder="Ex: 7" value="7" oninput="validateInputDuree()" disabled>
                                <div class="invalid-feedback">Veuillez entrer une durée!</div>
                            </div>
                            <div class="col-7 col-md-7 mb-2 mb-md-0">
                                <select id="boost_unite_duree" class="form-select form-control form-select-sm" name="unite_duree" required>
                                    <!--<option value="" disabled selected>Sélectionnez une unité</option>-->
                                    <option value="jour">Jour(s)</option>
                                    <!--<option value="semaine">Semaine(s)</option>
                                                            <option value="mois">Mois</option>
                                                            <option value="annee">Année</option>-->
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-check"></i> Valider
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteConfirmation{{ $bien->id }}" tabindex="-1" aria-labelledby="deleteConfirmationLabel{{ $bien->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header yes">
                <i class="bi bi-exclamation-triangle me-1"></i>
                <h5 class="modal-title" id="deleteConfirmationLabel{{ $bien->id }}">Confirmation de Suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-black">
                Êtes-vous sûr de vouloir supprimer cette annonce ? Cette action est irréversible.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Annuler</button>
                <form action="{{ route('announcement.destroy', $bien->id) }}" method="POST" class="d-inline" onsubmit="showLoading()">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i> Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation pour la publication -->
<div class="modal fade" id="publishConfirmation{{ $bien->id }}" tabindex="-1" aria-labelledby="publishConfirmationLabel{{ $bien->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header yes">
                <i class="bi bi-exclamation-triangle me-1"></i>
                <h5 class="modal-title" id="publishConfirmationLabel{{ $bien->id }}">Confirmation de Publication</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-black">
                Êtes-vous sûr de vouloir publier cette annonce ? Cette annonce sera disponible sur la plateforme.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Annuler</button>
                <form action="{{ route('announcement.publish', $bien->id) }}" method="POST" class="d-inline" onsubmit="showLoading()">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-warning"><i class="bi bi-check-circle"></i> Publier</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation pour la republication -->
<div class="modal fade" id="relaunchConfirmation{{ $bien->id }}" tabindex="-1" aria-labelledby="relaunchConfirmationLabel{{ $bien->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header yes">
                <i class="bi bi-exclamation-triangle me-1"></i>
                <h5 class="modal-title" id="relaunchConfirmationLabel{{ $bien->id }}">Confirmation de Republication</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-black">
                Êtes-vous sûr de vouloir republier cette annonce ? Cette annonce sera à noouveau disponible sur la plateforme.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Annuler</button>
                <form action="{{ route('announcement.relaunch', $bien->id) }}" method="POST" class="d-inline" onsubmit="showLoading()">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-warning"><i class="bi bi-check-circle"></i> Republier</button>
                </form>
            </div>
        </div>
    </div>
</div>
