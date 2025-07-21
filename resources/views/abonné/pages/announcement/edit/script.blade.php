<!-- Script pour les photos -->
<script>
    let existingPhotos = @json($existingPhotoIds);
    let deletedPhotos = [];
    let photoStatus = Array({{ $maxPhotos }}).fill(false);

    existingPhotos.forEach((photoId, index) => {
        if (photoId) {
            photoStatus[index] = true;
        }
    });

    function showModalMessage(message) {
        const modal = new bootstrap.Modal(document.getElementById('validationModal'));
        const messageContainer = document.getElementById('validationMessage');
        messageContainer.textContent = message;
        modal.show();
    }

    function setupPhotoPreview(inputId, previewId, removeButtonId, index) {
        const inputElement = document.getElementById(inputId);
        const previewElement = document.getElementById(previewId);
        const previewIcon = previewElement.nextElementSibling;
        const removeButton = document.getElementById(removeButtonId);
        const deletedPhotoInput = document.getElementById(`deleted_photo_${index}`);

        inputElement.addEventListener('change', function (event) {
            const file = event.target.files[0];

            if (index > 0 && !photoStatus[index - 1]) {
                showModalMessage("Vous devez d'abord ajouter la photo précédente avant de pouvoir ajouter celle-ci.");
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

                    photoStatus[index] = true;

                    const photoId = parseInt(removeButton.dataset.photoId);
                    if (photoId && deletedPhotos.includes(photoId)) {
                        deletedPhotos = deletedPhotos.filter(id => id !== photoId);
                        deletedPhotoInput.value = '';
                    }
                };
                reader.readAsDataURL(file);
            }
        });

        removeButton.addEventListener('click', function () {
            for (let i = index + 1; i < photoStatus.length; i++) {
                if (photoStatus[i]) {
                    showModalMessage("Vous devez d'abord supprimer les photos suivantes avant de pouvoir retirer celle-ci.");
                    return;
                }
            }

            const photoId = parseInt(removeButton.dataset.photoId);
            if (photoId) {
                deletedPhotos.push(photoId);
                deletedPhotoInput.value = photoId;
            }

            inputElement.value = '';
            previewElement.src = '';
            previewElement.classList.add('d-none');
            previewIcon.classList.remove('d-none');
            removeButton.classList.add('d-none');

            photoStatus[index] = false;
        });
    }

    const maxPhotos = {{ $maxPhotos }};
    for (let i = 0; i < maxPhotos; i++) {
        setupPhotoPreview(`photo_${i}`, `preview_photo_${i}`, `remove_photo_${i}`, i);
    }
</script>

<!-- Script pour les vidéos -->
<script>
    let existingVideos = @json($existingVideoIds);
    let deletedVideos = [];
    let videoStatus = Array({{ $maxVideos }}).fill(false);

    existingVideos.forEach((videoId, index) => {
        if (videoId) {
            videoStatus[index] = true;
        }
    });

    function showModalMessage(message) {
        const modal = new bootstrap.Modal(document.getElementById('validationModal'));
        const messageContainer = document.getElementById('validationMessage');
        messageContainer.textContent = message;
        modal.show();
    }

    function setupVideoPreview(inputId, previewId, removeButtonId, index) {
        const inputElement = document.getElementById(inputId);
        const previewElement = document.getElementById(previewId);
        const previewIcon = previewElement.nextElementSibling;
        const removeButton = document.getElementById(removeButtonId);
        const deletedVideoInput = document.getElementById(`deleted_video_${index}`);

        inputElement.addEventListener('change', function (event) {
            const file = event.target.files[0];

            if (index > 0 && !videoStatus[index - 1]) {
                showModalMessage("Vous devez d'abord ajouter la vidéo précédente avant de pouvoir ajouter celle-ci.");
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

                    videoStatus[index] = true;

                    const videoId = parseInt(removeButton.dataset.videoId);
                    if (videoId && deletedVideos.includes(videoId)) {
                        deletedVideos = deletedVideos.filter(id => id !== videoId);
                        deletedVideoInput.value = '';
                    }
                };
                reader.readAsDataURL(file);
            }
        });

        removeButton.addEventListener('click', function () {
            for (let j = index + 1; j < videoStatus.length; j++) {
                if (videoStatus[j]) {
                    showModalMessage("Vous devez d'abord supprimer la vidéo suivante avant de pouvoir retirer celle-ci.");
                    return;
                }
            }

            const videoId = parseInt(removeButton.dataset.videoId);
            if (videoId) {
                deletedVideos.push(videoId);
                deletedVideoInput.value = videoId;
            }

            inputElement.value = '';
            previewElement.src = '';
            previewElement.classList.add('d-none');
            previewIcon.classList.remove('d-none');
            removeButton.classList.add('d-none');

            videoStatus[index] = false;
        });
    }

    const maxVideos = {{ $maxVideos }};
    for (let j = 0; j < maxVideos; j++) {
        setupVideoPreview(`video_${j}`, `preview_video_${j}`, `remove_video_${j}`, j);
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var categories = @json($categories);
        var bien = @json($bien);

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
                var parameterId = assoc.parametre.id;
                var parameterName = assoc.parametre.nom_parametre;

                var parameterValue = bien.valeurs.find(function (val) {
                    return val.id_association_categorie === assoc.id;
                })?.valeur || '';

                var inputGroup = document.createElement('div');
                inputGroup.classList.add('mb-3', 'row');

                var labelCol = document.createElement('div');
                labelCol.classList.add('col-md-7');

                var label = document.createElement('label');
                label.textContent = parameterName;
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
                input.value = parameterValue;
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

        var initialCategoryId = categorySelect.value;
        if (initialCategoryId) {
            updateParameters(initialCategoryId);
        }

        categorySelect.addEventListener('change', function () {
            var selectedCategoryId = this.value;
            updateParameters(selectedCategoryId);
        });
    });
</script>
