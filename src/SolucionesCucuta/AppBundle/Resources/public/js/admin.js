/**
 * Created by hender on 28/05/15.
 */
function checkForm(este){
    var input = $(este), label = input.siblings('label'), padre = input.parent();
    if(input.parents('form.no-style').length){
        label.addClass('uk-form-label');
        padre.addClass('uk-form-row');
        input.addClass('uk-form-small');
        input.addClass('uk-form-width-small');
    }else{
        label.addClass('uk-form-label');
        padre.addClass('uk-form-row');
        input.addClass('uk-form-large');
        input.addClass('uk-form-width-large');
    }
    if(input.parent('th').length){
        input.addClass('uk-form-blank uk-text-center');
    }
}
$(function(){
    var forms = $('form');
    if(forms.length){
        forms.addClass('uk-form');
        forms.not('.no-style').addClass('uk-form-horizontal uk-width-small-1-1 uk-width-medium-7-10 uk-container-center');
        forms.children().find("input, select, textarea").each(function(){
            checkForm(this);
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