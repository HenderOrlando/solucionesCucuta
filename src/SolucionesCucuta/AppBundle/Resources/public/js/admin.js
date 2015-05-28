/**
 * Created by hender on 28/05/15.
 */

$(function(){
    var forms = $('form');
    if(forms.length){
        forms.addClass('uk-form uk-form-horizontal uk-width-small-1-1 uk-width-medium-7-10 uk-container-center');
        $('input, select, textarea').each(function(){
            var input = $(this), label = input.siblings('label'), padre = input.parent();
            label.addClass('uk-form-label');
            padre.addClass('uk-form-row');
            input.addClass('uk-form-large');
            input.addClass('uk-form-width-large');
        });
        forms.find('button').addClass('uk-button uk-button-large');
        var autocomplete = '<div class="uk-autocomplete uk-form"><input type="text"></div>';
        var selects = forms.find('select');
        if(selects.length){
            selects.each(function(){
                $(this).select2();
            });
        }
    }
});