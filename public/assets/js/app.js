(function ($) {

    $('.close-button').click(function () {
        var $callout = $(this).parent();
        $callout.slideUp(500, function() { $(this).remove() });
    });

    var del = function (e) {
        e.preventDefault();
        var $target = $(this).closest('article');

        $.ajax({
            url : '/'+($target.attr('data-ressource-type'))+'/'+($target.attr('data-id'))+'/delete?__token='+$('#__token').val(),
            type: 'delete',
            success :  function (data) {
                $target.animate({
                    height : 0
                }, 500, function () {
                    $target.remove();
                });
            }
        });
        return false;
    };

    var star = function (e) {
        e.preventDefault();
        $starButton = $(this);
        var $target = $(this).closest('article');
        $.ajax({
            url : '/replies/'+$target.attr('data-id')+'/star',
            type: 'get',
            success : function (data) {
                var $starText = $target.find('.star');
                $starText.text(Number($starText.text())+1);
                $starButton.remove();
            }
        })
    }

    $('.del-button').click(del);
    $('.star-button').click(star);

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
                var d = new Date(data.reply.created_at);
                data.reply.created_at = d.toLocaleDateString();
                Object.keys(data.reply).forEach(function (key) {
                    text = text.split('{{ '+key+' }}').join(data.reply[key]);
                });
                $('.replies-content').prepend(text);
                $('.replies-content article:first').css('opacity',0).animate({opacity:1},500);
                $('.replies-content article:first .del-button').click(del);
                $('.replies-content article:first .star-button').click(star);
            }
        });
    });

})(jQuery);
