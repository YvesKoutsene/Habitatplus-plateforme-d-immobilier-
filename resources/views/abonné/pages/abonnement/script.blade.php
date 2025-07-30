<script>
    const modal = document.getElementById('abonnementProModal');
    const dureeInput = modal.querySelector('#dureeInput');
    const prixUnitaire = {{ $modele->prix }};
    const remise12Mois = 25000;

    const etape1 = modal.querySelector('#etape1');
    const etape2 = modal.querySelector('#etape2');
    const etape3 = modal.querySelector('#etape3');
    const etape4 = modal.querySelector('#etape4');

    const prixUnitaireText = modal.querySelector('#prixUnitaireText');
    const factureDuree = modal.querySelector('#factureDuree');
    const factureTotal = modal.querySelector('#factureTotal');
    const remiseInfo = modal.querySelector('#remiseInfo');
    const hiddenDuree = modal.querySelector('#hiddenDuree');
    const hiddenMode = modal.querySelector('#hiddenMode');

    const confirmDuree = modal.querySelector('#confirmDuree');
    const confirmTotal = modal.querySelector('#confirmTotal');
    const confirmMode = modal.querySelector('#confirmMode');

    const btnWallet = document.getElementById('btnWallet');
    const btnMobile = document.getElementById('btnMobile');

    let totalFinal = 0;
    let modePaiement = '';

    modal.addEventListener('show.bs.modal', function (event) {
        etape1.classList.remove('d-none');
        etape2.classList.add('d-none');
        etape3.classList.add('d-none');
        etape4.classList.add('d-none');
        dureeInput.value = '';
        hiddenDuree.value = '';
        hiddenMode.value = '';
        totalFinal = 0;
        modePaiement = '';
        btnWallet.classList.remove('active');
        btnMobile.classList.remove('active');

        const form = modal.querySelector('form');
        const button = event.relatedTarget;
        const isRenew = button.getAttribute('data-renew') === 'true';
        const titre = button.getAttribute('data-titre') || 'Renouveler l\'abonnement Pro';
        modal.querySelector('.modal-title').textContent = titre;

        form.action = isRenew ? "{{ route('subscription.update') }}" : "{{ route('subscription.store') }}";

    });

    document.getElementById('btnAfficherFacture').addEventListener('click', () => {
        const duree = parseInt(dureeInput.value);
        if (isNaN(duree) || duree < 1) {
            alert("Veuillez entrer une durée valide (minimum 1 mois).");
            return;
        }

        hiddenDuree.value = duree;
        factureDuree.textContent = `${duree} mois`;
        prixUnitaireText.textContent = `${prixUnitaire.toLocaleString('fr-FR')} FCFA`;

        if (duree === 12) {
            totalFinal = remise12Mois;
            factureTotal.textContent = `${totalFinal.toLocaleString('fr-FR')} FCFA`;
            remiseInfo.classList.remove('d-none');
        } else {
            totalFinal = prixUnitaire * duree;
            factureTotal.textContent = `${totalFinal.toLocaleString('fr-FR')} FCFA`;
            remiseInfo.classList.add('d-none');
        }

        etape1.classList.add('d-none');
        etape2.classList.remove('d-none');
    });

    document.getElementById('btnChoisirPaiement').addEventListener('click', () => {
        etape2.classList.add('d-none');
        etape3.classList.remove('d-none');
    });

    function choisirMode(mode) {
        modePaiement = mode;
        hiddenMode.value = mode;

        btnWallet.classList.remove('active');
        btnMobile.classList.remove('active');

        if (mode === 'wallet') {
            btnWallet.classList.add('active');
        } else {
            btnMobile.classList.add('active');
        }
    }

    document.getElementById('btnConfirmerFinal').addEventListener('click', () => {
        const duree = hiddenDuree.value;
        if (!modePaiement) {
            alert("Veuillez choisir un mode de paiement.");
            return;
        }

        confirmDuree.textContent = duree;
        confirmTotal.textContent = totalFinal.toLocaleString('fr-FR');
        confirmMode.textContent = modePaiement === 'wallet' ? 'Portefeuille' : 'Mobile Money';

        etape3.classList.add('d-none');
        etape4.classList.remove('d-none');
    });

    function validateInputDuree() {
        const input = document.getElementById('dureeInput');
        input.value = input.value.replace(/[^0-9]/g, '');
        if (input.value.length > 3) {
            input.value = input.value.substring(0, 3);
        }
    }
</script>
