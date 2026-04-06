$(function () {

    const BASE    = window.APP_BASE || '/prwb_2526_c04/';
    const form    = $('#item-form');
    const saveBtn = $('.save-btn');
    const itemId  = form.data('item-id') || null;

    let config     = {};
    let touched    = {};
    let errors     = {};
    let titleTimer = null;

    // ── 1. Récupération des constantes de validation ───────────────────────
    $.getJSON(BASE + 'config/validation_service', function (data) {
        config = data;
    });

    // ── 2. Références aux champs ───────────────────────────────────────────
    const $title       = $('input[name="title"]');
    const $desc        = $('textarea[name="description"]');
    const $duration    = $('input[name="duration_days"]');
    const $startingBid = $('input[name="starting_bid"]');
    const $buyNow      = $('input[name="buy_now_price"]');
    const $salePrice   = $('input[name="sale_price"]');

    // ── 3. Helpers UI ──────────────────────────────────────────────────────
    function setError(key, msg, $input) {
        errors[key] = msg;
        if (!$input) { updateBtn(); return; }

        // MODIFICATION : on applique la classe sur l'input directement
        $input.removeClass('is-valid').addClass('is-invalid');

        let $fb = $input.siblings('.js-feedback');
        if (!$fb.length) {
            $fb = $('<div class="js-feedback"></div>');
            $input.after($fb);
        }
        $fb.text(msg);
        updateBtn();
    }

    function setValid(key, $input) {
        delete errors[key];
        if (!$input) { updateBtn(); return; }

        // MODIFICATION : on applique la classe sur l'input directement
        $input.removeClass('is-invalid').addClass('is-valid');

        let $fb = $input.siblings('.js-feedback');
        if ($fb.length) $fb.text('');
        updateBtn();
    }

    function resetField($input) {
        // MODIFICATION : on retire les classes de l'input directement
        $input.removeClass('is-invalid is-valid');
        $input.siblings('.js-feedback').text('');
    }

    function updateBtn() {
        saveBtn.prop('disabled', Object.keys(errors).length > 0);
    }

    // ── 4. Validations synchrones ──────────────────────────────────────────
    function validateTitle() {
        if (!touched.title) return;

        const val = $title.val().trim();
        const min = config.title_min_length || 3;
        const max = config.title_max_length || 255;

        if (!val) {
            setError('title', 'Le titre est obligatoire.', $title);
        } else if (val.length < min) {
            setError('title', `Le titre doit faire au moins ${min} caractères.`, $title);
        } else if (val.length > max) {
            setError('title', `Le titre ne peut pas dépasser ${max} caractères.`, $title);
        } else {
            checkTitleAsync(val);
        }
    }

    function validateDescription() {
        if (!touched.description) return;

        const val = $desc.val().trim();
        const min = config.description_min_length || 3;

        if (val && val.length < min) {
            setError('description', `La description doit faire au moins ${min} caractères.`, $desc);
        } else {
            setValid('description', $desc);
        }
    }

    function validateDuration() {
        if (!touched.duration_days) return;

        const val = parseInt($duration.val(), 10);
        const min = config.duration_min_days || 1;
        const max = config.duration_max_days || 365;

        if (isNaN(val) || val < min || val > max) {
            setError('duration_days', `La durée doit être entre ${min} et ${max} jours.`, $duration);
        } else {
            setValid('duration_days', $duration);
        }
    }

    function validatePricing() {
        const sb = $startingBid.val().trim();
        const bn = $buyNow.val().trim();
        const sp = $salePrice.val().trim();

        // Réinitialiser les états pricing
        ['starting_bid', 'buy_now_price', 'sale_price'].forEach(k => delete errors[k]);
        [$startingBid, $buyNow, $salePrice].forEach($i => resetField($i));

        if (sp !== '') {
            // Mode vente directe
            if (!touched.sale_price) { updateBtn(); return; }
            const spVal = parseFloat(sp);
            if (isNaN(spVal) || spVal <= 0) {
                setError('sale_price', 'Le prix de vente doit être un nombre positif.', $salePrice);
            } else {
                setValid('sale_price', $salePrice);
            }

        } else if (sb !== '') {
            // Mode enchère
            if (touched.starting_bid) {
                const sbVal = parseFloat(sb);
                if (isNaN(sbVal) || sbVal <= 0) {
                    setError('starting_bid', 'La mise de départ doit être un nombre positif.', $startingBid);
                } else {
                    setValid('starting_bid', $startingBid);

                    if (bn !== '' && touched.buy_now_price) {
                        const bnVal = parseFloat(bn);
                        if (isNaN(bnVal) || bnVal <= sbVal) {
                            setError('buy_now_price', 'Le prix d\'achat immédiat doit être supérieur à la mise de départ.', $buyNow);
                        } else {
                            setValid('buy_now_price', $buyNow);
                        }
                    }
                }
            }

        } else {
            // Aucun mode renseigné
            if (touched.sale_price || touched.starting_bid) {
                setError('sale_price', 'Veuillez renseigner un prix de vente ou une mise de départ.', $salePrice);
            }
        }

        updateBtn();
    }

    // ── 5. Validation asynchrone (unicité du titre) ────────────────────────
    function checkTitleAsync(title) {
        clearTimeout(titleTimer);
        titleTimer = setTimeout(function () {
            $.ajax({
                url     : BASE + 'item/check_title_service',
                method  : 'POST',
                data    : { title: title, item_id: itemId || '' },
                dataType: 'json',
                success : function (res) {
                    if (res.available) {
                        setValid('title', $title);
                    } else {
                        setError('title', 'Vous avez déjà une annonce avec ce titre.', $title);
                    }
                }
            });
        }, 400);
    }

    // ── 6. Événements ──────────────────────────────────────────────────────
    $title.on('input blur', function () {
        touched.title = true;
        validateTitle();
    });

    $desc.on('input blur', function () {
        touched.description = true;
        validateDescription();
    });

    $duration.on('input blur', function () {
        touched.duration_days = true;
        validateDuration();
    });

    $startingBid.on('input blur', function () {
        touched.starting_bid = true;
        validatePricing();
    });

    $buyNow.on('input blur', function () {
        touched.buy_now_price = true;
        validatePricing();
    });

    $salePrice.on('input blur', function () {
        touched.sale_price = true;
        validatePricing();
    });

    // ── 7. Soumission : tout marquer comme touché avant validation ─────────
    form.on('submit', function (e) {
        touched = {
            title        : true,
            description  : true,
            duration_days: true,
            starting_bid : true,
            buy_now_price: true,
            sale_price   : true
        };

        validateTitle();
        validateDescription();
        validateDuration();
        validatePricing();

        if (Object.keys(errors).length > 0) {
            e.preventDefault();
        }
    });


    // ── 8. GESTION DES CHANGEMENTS NON SAUVEGARDÉS (Unsaved changes) ─────────
    
    let isDirty = false;
    let targetUrl = ''; // Pour stocker le lien sur lequel l'utilisateur a cliqué

    // 8.1 On marque le formulaire comme "dirty" dès qu'un champ change
    form.find('input, textarea, select').on('input change', function() {
        isDirty = true;
    });

    // 8.2 On retire le statut "dirty" si l'utilisateur soumet volontairement le formulaire (le clic sur Save)
    form.on('submit', function() {
        // Seulement si le formulaire est valide (pas d'erreurs)
        if (Object.keys(errors).length === 0) {
            isDirty = false;
        }
    });

    // 8.3 Intercepter les clics sur les liens internes (Navbar, Bouton retour, etc.)
    $('a').on('click', function(e) {
        if (isDirty) {
            e.preventDefault(); // On empêche la navigation
            targetUrl = $(this).attr('href'); // On garde le lien en mémoire
            $('#unsavedModal').fadeIn('fast'); // On affiche la modale
        }
    });

    // 8.4 Gestion des boutons de la modale
    $('#cancelLeaveBtn, #closeUnsavedCross').on('click', function() {
        $('#unsavedModal').fadeOut('fast');
        targetUrl = ''; // On annule la navigation
    });

    $('#confirmLeaveBtn').on('click', function() {
        isDirty = false; // On désactive la vérification
        window.location.href = targetUrl; // On redirige vers le lien stocké
    });

    // 8.5 Intercepter la fermeture de l'onglet, le bouton "Précédent" du navigateur ou F5
    // Attention : Pour des raisons de sécurité, les navigateurs modernes affichent LEUR PROPRE modale standard,
    // on ne peut pas forcer le design de NOTRE modale pour un F5 ou une fermeture d'onglet.
    window.addEventListener('beforeunload', function(e) {
        if (isDirty) {
            // Le message exact est souvent ignoré par les navigateurs modernes, mais il faut le définir pour déclencher la modale native.
            const confirmationMessage = 'You have unsaved changes. Leave anyway?';
            e.returnValue = confirmationMessage;
            return confirmationMessage;
        }
    });

});
