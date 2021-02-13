$(document).ready(function($) {
    $('.popup-open').click(function() {
        $('.popup-fade').fadeIn();
        return false;
    });

    $('.popup-close').click(function() {
        $(this).parents('.popup-fade').fadeOut();
        return false;
    });

    $('.popup-fade').click(function (e){
        if($(e.taget).closest('.poup').length == 0){
            $(this).fadeOut();
        }
    });

});