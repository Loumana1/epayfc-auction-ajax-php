$(document).ready(function () {
    const item_id = $('#item-carousel').data('item-id');
    if (!item_id) return;

    // recup les images
    $.getJSON('open_item/pictures_service/' + item_id, function (pictures) {
        if (!pictures || pictures.length === 0) return;

        //  carousel
        const $inner = $('#carousel-inner');
        pictures.forEach(function (pic, index) {
            const active = index === 0 ? 'active' : '';
            $inner.append(
                '<div class="carousel-item ' + active + '">' +
                '  <img src="' + pic.path + '" class="d-block w-100" alt="">' +
                '</div>'
            );
        });

        // vignettes cliquables
        const $thumbs = $('.thumbnail-gallery');
        $thumbs.empty();
        pictures.forEach(function (pic, index) {
            const active_class = index === 0 ? 'active' : '';
            $thumbs.append(
                '<img class="thumbnail ' + active_class + '" ' +
                'src="' + pic.thumbnail + '" ' +
                'data-bs-target="#item-carousel" data-bs-slide-to="' + index + '" ' +
                'alt="Thumbnail">'
            );
        });

        // action clic sur vignette
        $thumbs.on('click', '.thumbnail', function () {
            const index = $(this).data('bs-slide-to');
            $('#item-carousel').carousel(index);
            $thumbs.find('.thumbnail').removeClass('active');
            $(this).addClass('active');
        });

        // Sync vignette active quand le carousel slide
        $('#item-carousel').on('slid.bs.carousel', function (e) {
            $thumbs.find('.thumbnail').removeClass('active');
            $thumbs.find('.thumbnail').eq(e.to).addClass('active');
        });

        // Afficher le carousel ou masquer l'image statique
        $('#item-carousel').removeClass('d-none');
        $('#static-main-image').addClass('d-none');
        // Masquer les liens <a> des vignettes (version sans JS)
        $('.thumbnail-gallery a').contents().unwrap();
    });
});