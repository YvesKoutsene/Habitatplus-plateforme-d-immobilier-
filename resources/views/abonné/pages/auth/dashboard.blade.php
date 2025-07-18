@extends('abonné.include.layouts.apps')
@section('content')

<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="profil" role="tabpanel" aria-labelledby="profil-tab">
        @include('abonné.pages.auth.profile')
    </div>
    <div class="tab-pane fade" id="annonces" role="tabpanel" aria-labelledby="annonces-tab">
        @include('abonné.pages.announcement.index')
    </div>

    <!--
    <div class="tab-pane fade" id="favoris" role="tabpanel" aria-labelledby="favoris-tab">
    </div>
    <div class="tab-pane fade" id="alertes" role="tabpanel" aria-labelledby="alertes-tab">
    </div>
    -->

    <div class="tab-pane fade" id="abonnements" role="tabpanel" aria-labelledby="abonnements-tab">
         @include('abonné.pages.abonnement.index')
    </div>

</div>

<!-- Pour se rendre directement sur l'onglet abonnement -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const targetTab = urlParams.get('onglet');

        if (targetTab) {
            const tabTriggerEl = document.querySelector(`button[data-bs-target="#${targetTab}"]`);
            if (tabTriggerEl) {
                const tab = new bootstrap.Tab(tabTriggerEl);
                tab.show();
            }
        }
    });
</script>

@endsection
