$(function () {
      $('.bid-form').attr('novalidate', 'novalidate');
    $('.bid-form').on('submit', function (e) {
        e.preventDefault();
        var $form = $(this);
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
                    : 'An error occurred';
                $feedback.removeClass('bid-success').addClass('bid-error')
                    .text(msg).fadeIn();
            }
        });
    });
});