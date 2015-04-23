/**
 * Created by hender on 21/04/15.
 */
$(function(){
    $('[data-uk-slideset]').each(function(){

    });
    setTimeout(function(){
        $('[data-uk-slideset]').each(function(){
            $(this).find('.uk-hidden ').removeClass('uk-hidden');
        });
    }, 1000);
});