/**
 * Created by hender on 28/05/15.
 */

$(function(){
    var forms = $('form');
    if(forms.length){
        forms.addClass('uk-form uk-form-horizontal');
        $('input, select, textarea').each(function(){
            var input = $(this), label = input.siblings('label'), padre = input.parent();
            label.addClass('uk-form-label');
            padre.addClass('uk-form-row');
        });
        forms.find('button').addClass('uk-button uk-button-large');
    }
});