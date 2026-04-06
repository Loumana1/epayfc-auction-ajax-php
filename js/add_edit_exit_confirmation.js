$(document).ready(function () {
    const $form = $('form#item-form');
    if ($form.length === 0) return;

    let is_dirty = false;

    // Sauvegarder  input intial 
    const initial_values = {};
    $form.find('input, textarea, select').each(function () {
        initial_values[this.name] = $(this).val();
    });

    // ecoute et detect les modif
    $form.on('input change', 'input, textarea, select', function () {
        is_dirty = false;
        $form.find('input, textarea, select').each(function () {
            if ($(this).val() !== initial_values[this.name]) {
                is_dirty = true;
            }
        });
    });

    // Intercepter la soumission sans bloqu
    let is_submitting = false;
    $form.on('submit', function () {
        is_submitting = true;
    });

    // Intercepter les clic sur les lien de nav
    let pending_href = null;

    $(document).on('click', 'a[href]', function (e) {
        if (!is_dirty || is_submitting) return;

        e.preventDefault();
        pending_href = $(this).attr('href');
        bootstrap.Modal.getOrCreateInstance(document.getElementById('unsaved-modal')).show();
    });

    // Bouton "Leave" 
    $('#unsaved-confirm-leave').on('click', function () {
        is_dirty = false;
        bootstrap.Modal.getOrCreateInstance(document.getElementById('unsaved-modal')).hide();
        if (pending_href) {
            window.location.href = pending_href;
        }
    });

    // Intercepter bouton back navigateur, fermeture onglet
    $(window).on('beforeunload', function (e) {
        if (is_dirty && !is_submitting) {
            e.preventDefault();
            return '';
        }
    });
});