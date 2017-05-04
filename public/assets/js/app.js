(function ($) {

    var del = function (e) {
        e.preventDefault();
        var target = $(this).closest('article');

        $.ajax({
            url : '/'+(target.attr('data-ressource-type'))+'/'+(target.attr('data-id'))+'/delete',
            contentType : 'application/json',
            success :  function (data) {
                target.animate({
                    height : 0
                }, 500, function () {
                    target.remove();
                });
            }
        });
    };

    $('.del-button').click(del);

    $('#reply-form').submit(function (e) {
        e.preventDefault();
        $.ajax({
            type : 'post',
            url : '/replies',
            data : $(this).serialize(),
            contentType : 'application/x-www-form-urlencoded',
            dataType : 'json',
            success :  function (data) {
                $('#reply-form textarea').val('');
                var text = $('.reply-template').html();
                Object.keys(data.reply).forEach(function (key) {
                    text = text.replace('{{ '+key+' }}',data.reply[key]);
                });
                $('.replies-content').prepend(text);
                $('.replies-content article:first').css('opacity',0).animate({opacity:1},500);
                $('.del-button').click(del);
            }
        });
    });

})(jQuery);
