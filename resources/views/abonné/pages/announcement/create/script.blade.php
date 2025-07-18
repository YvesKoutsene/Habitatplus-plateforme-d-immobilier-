<script>
    /* Pour faire appel au modal pour les erreurs nécessaires à afficher */
    function showModal(message) {
        document.getElementById('validationMessage').innerText = message;
        const modal = new bootstrap.Modal(document.getElementById('validationModal'));
        modal.show();
    }

    /* Pour l'affichage de la fenetre modale de passer à Pro (Photo)*/
    document.addEventListener('DOMContentLoaded', function () {
        const uploadIcons = document.querySelectorAll('.upload-icon');

        uploadIcons.forEach(icon => {
            icon.addEventListener('click', function (e) {
                const isLocked = this.dataset.locked === 'true';
                const index = this.dataset.photoIndex;

                if (isLocked) {
                    const modal = new bootstrap.Modal(document.getElementById('upgradeModal'));
                        modal.show();
                    } else {
                    const input = document.querySelector(`input[type="file"][data-photo-index="${index}"]`);
                        if (input) input.click();
                }
            });
        });
    });

    /* Pour l'affichage de la fenetre modale de passer à Pro (Vidéo)*/
    document.addEventListener('DOMContentLoaded', function () {
        const videoIcons = document.querySelectorAll('[data-video-index]');

        videoIcons.forEach(icon => {
            icon.addEventListener('click', function () {
                const isLocked = this.dataset.videoLocked === 'true';
                const index = this.dataset.videoIndex;

                if (isLocked) {
                    const modal = new bootstrap.Modal(document.getElementById('videoUpgradeModal'));
                    modal.show();
                } else {
                    const input = document.querySelector(`input[type="file"][data-video-index="${index}"]`);
                    if (input) input.click();
                }
            });
        });
    });

    /* Pour les photos */
    function setupPhotoPreview(inputId, previewId, removeButtonId, index) {
        const inputElement = document.getElementById(inputId);
        const previewElement = document.getElementById(previewId);
        const previewIcon = previewElement.nextElementSibling;
        const removeButton = document.getElementById(removeButtonId);

        inputElement.addEventListener('change', function (event) {
            const file = event.target.files[0];

            if (index > 0 && !document.getElementById(`photo_${index - 1}`).value) {
                showModal("Vous devez d'abord ajouter la photo précédente avant de pouvoir ajouter celle-ci.");
                inputElement.value = '';
                return;
            }

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewElement.src = e.target.result;
                    previewElement.classList.remove('d-none');
                    previewIcon.classList.add('d-none');
                    removeButton.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }
        });

        removeButton.addEventListener('click', function () {
            for (let i = index + 1; i < {{ $maxPhotos }}; i++) {
                if (document.getElementById(`photo_${i}`).value) {
                    showModal("Vous devez d'abord supprimer les photos suivantes avant de pouvoir retirer celle-ci.");
                    return;
                }
            }

            inputElement.value = '';
            previewElement.src = '';
            previewElement.classList.add('d-none');
            previewIcon.classList.remove('d-none');
            removeButton.classList.add('d-none');
        });
    }

    const maxPhotos = {{ $maxPhotos }};
    for (let i = 0; i < maxPhotos; i++) {
        setupPhotoPreview(`photo_${i}`, `preview_photo_${i}`, `remove_photo_${i}`, i);
    }

    /* Pour les videos */
    function setupVideoPreview(inputId, previewId, removeButtonId, index) {
        const inputElement = document.getElementById(inputId);
        const previewElement = document.getElementById(previewId);
        const previewIcon = previewElement.nextElementSibling;
        const removeButton = document.getElementById(removeButtonId);

        inputElement.addEventListener('change', function (event) {
            const file = event.target.files[0];

            if (index > 0 && !document.getElementById(`video_${index - 1}`).value) {
                showModal("Vous devez d'abord ajouter la video précédente avant de pouvoir ajouter celle-ci.");
                inputElement.value = '';
                return;
            }

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewElement.src = e.target.result;
                    previewElement.classList.remove('d-none');
                    previewIcon.classList.add('d-none');
                    removeButton.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }
        });

        removeButton.addEventListener('click', function () {
            for (let j = index + 1; j < {{ $maxVideos }}; j++) {
                if (document.getElementById(`video_${j}`).value) {
                    showModal("Vous devez d'abord supprimer la vidéo suivante avant de pouvoir retirer celle-ci.");
                    return;
                }
            }

            inputElement.value = '';
            previewElement.src = '';
            previewElement.classList.add('d-none');
            previewIcon.classList.remove('d-none');
            removeButton.classList.add('d-none');
        });
    }

    const maxVideos = {{ $maxVideos }};
    for (let j = 0; j < maxVideos; j++) {
        setupVideoPreview(`video_${j}`, `preview_video_${j}`, `remove_video_${j}`, j);
    }
</script>

<script>
    function validateInput01() {
        const input = document.getElementById('prix_annonce');
        input.value = input.value.replace(/[^0-9]/g, '');

        if (input.value.length > 10) {
            input.value = input.value.substring(0, 10);
        }
    }

    document.getElementById('createAdForm').addEventListener('submit', function(event) {
        const action = event.submitter.value;
        const requiredFields = ['categorySelect', 'title'];
        let isValid = true;

        if (action === 'publish') {
            requiredFields.push('prix_annonce', 'location', 'description', 'type_annonce');

            const parameterFields = document.querySelectorAll('#parametersContainer input');
            parameterFields.forEach(field => {
                requiredFields.push(field.id);
            });
        }
        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field && !field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
            } else if (field) {
                field.classList.remove('is-invalid');
            }
        });
        if (!isValid) {
            event.preventDefault();
            const message = 'Veuillez remplir tous les champs obligatoires pour publier.';
            document.getElementById('validationMessage').textContent = message;
            const validationModal = new bootstrap.Modal(document.getElementById('validationModal'));
            validationModal.show();
        }
    });

</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var categories = @json($categories);

        var categorySelect = document.getElementById('categorySelect');
        var parametersContainer = document.getElementById('parametersContainer');

        function validateInput(input) {
            input.value = input.value.replace(/[^0-9]/g, '');

            if (input.value.length > 10) {
                input.value = input.value.substring(0, 10);
            }
        }

        function updateParameters(categoryId) {
            parametersContainer.innerHTML = '';

            var selectedCategory = categories.find(function (cat) {
                return cat.id === parseInt(categoryId);
            });

            if (!selectedCategory) {
                parametersContainer.innerHTML = '<p>Aucun paramètre disponible pour cette catégorie.</p>';
                return;
            }

            selectedCategory.associations.forEach(function (assoc) {
                var parameterId = assoc.id_parametre;

                var inputGroup = document.createElement('div');
                inputGroup.classList.add('mb-3', 'row');

                var labelCol = document.createElement('div');
                labelCol.classList.add('col-md-7');

                var label = document.createElement('label');
                label.textContent = assoc.parametre.nom_parametre;
                label.classList.add('form-label');

                var requiredIndicator = document.createElement('span');
                requiredIndicator.textContent = '*';
                requiredIndicator.classList.add('text-danger');
                requiredIndicator.title = "obligatoire pour publier votre annonce";

                labelCol.appendChild(label);
                labelCol.appendChild(requiredIndicator);

                var inputCol = document.createElement('div');
                inputCol.classList.add('col-md-5');

                var input = document.createElement('input');
                input.type = 'text';

                input.name = 'parameters[' + assoc.id + ']';

                input.id = 'param_' + parameterId;
                input.classList.add('form-control');
                input.placeholder = "Entrez une valeur";
                input.required = true;

                input.addEventListener('input', function () {
                    validateInput(input);
                });

                inputCol.appendChild(input);
                inputGroup.appendChild(labelCol);
                inputGroup.appendChild(inputCol);

                parametersContainer.appendChild(inputGroup);
            });
        }

        categorySelect.addEventListener('change', function () {
            var selectedCategoryId = this.value;
            updateParameters(selectedCategoryId);
        });
    });
</script>
