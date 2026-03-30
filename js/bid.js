
//message resspons qd on clique sur le boutton place bid 
$(function () {
      $('.bid-form').attr('novalidate', 'novalidate');
    $('.bid-form').on('submit', function (e) {
        e.preventDefault();
        var $form = $(this);

          //  Anti double-submit
    if ($form.data('bidPending')) return;
    $form.data('bidPending', true);



        var $feedback = $('#bid-feedback');



        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize() + '&format=json',
            success: function (data) {
                $feedback.removeClass('bid-error').addClass('bid-success')
                    .text(data.message).fadeIn();
                setTimeout(function () { location.reload(); }, 1500);
            },
            error: function (xhr) {
                var res = xhr.responseJSON;
                var msg = res && res.errors 
                    ? Object.values(res.errors).join(', ') 
                    : '';
                $feedback.removeClass('bid-success').addClass('bid-error')
                    .text(msg).fadeIn();
            },
            complete: function (jqXHR, textStatus) {
                // unlock only when it wasn't an HTTP success
                if (textStatus === 'success') return;
                $form.removeData('bidPending');
              }

        });
    });
});