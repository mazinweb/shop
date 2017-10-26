$(function () {
    'use strict';
    $('[placeholder]').focus(function () {
        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder', '');
    }).blur(function () {
        $(this).attr('placeholder', $(this).attr('data-text'));
    });

    //add asterisk of required filled
    $('input').each(function () {
        if($(this).attr('required') == 'required'){
            $(this).after('<span class="asterisk">*</span>')
        }
    });
    var passfield = $('.password');
    $('.show-pass').hover(function () {
        passfield.attr('type' , 'text');
        
    },function () {
        passfield.attr('type' , 'password');
    });

    $('.confirm').click(function () {
        return confirm('Are You Sure ?');
    });

});