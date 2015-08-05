/**
 * Created by hender on 21/04/15.
 */

$(function(){
    $('#registroUsuario').submit(function(e){
        e.preventDefault();
        $.ajax({
            url : $(this).attr('action'),
            type : "POST",
            data : $(this).serialize(),
            before : function () {

            },
            success : function (data) {
                console.log(data)
                if(data.msg){
                    UIkit.notify({
                        message : data.msg,
                        status  : data.error?'danger':'info',
                        timeout : 5000,
                        pos     : 'top-center'
                    });
                }
            },
            error : function () {

            }
        });
    });
    $(document).on('display.uk.check','[data-id]', function(){
        console.log('/////////////');
        console.log($(this));
        console.log('/////////////');
    });
    $('[data-uk-slideset]').on('show.uk.slideset',function(e, slideset){
        slideset.each(function(){
            var este = $(this)
                id = este.find('[data-id]').first().attr('data-id')
            ;
            ajaxSuma(id, 'print');
        });
    });
    $('[data-uk-slideshow]').on('show.uk.slideshow',function(e, slideshow){
        console.log('+++++++++++++');
        console.log(slideshow);
        console.log('+++++++++++++');
    });
    /*$('[data-uk-slideset] [data-id]:visible').each(function(){
        var id = $(this).attr('data-id');
        ajaxSuma(id, 'print');
    });*/
    var search = '[data-id]'
    if($(search+':visible').length){
        search += ':visible';
    }
    $(search).each(function(){
        var id = $(this).attr('data-id');
        ajaxSuma(id, 'print');
    });
    $('.uk-search').on('keyup keypress', function(e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            e.preventDefault();
            return false;
        }
    });

    $('body').on('click','a[data-id]', function(e){
        var este = $(this);
        ajaxSuma(este.attr('data-id'), 'click', este.attr('href'));
        e.preventDefault();
        e.stopPropagation();
    });

    function ajaxSuma(id, event, href){
        if(id && id.length > 0 && id != '#'){
            var url = urlSuma.replace('evento',event).replace('id',id);
            $.ajax({
                method: 'POST',
                url: url
            }).done(function(data){
                //console.log(data);
                if(href){
                    window.location = href;
                }
            });
        }
    }
});