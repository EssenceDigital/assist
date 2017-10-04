jQuery(document).ready(function ($) {
    'use strict';
    
    $('.mnu-inn > ul > li a').on('click', function () {
        $('.mnu-wrp').removeClass('active');
        return false;
    });
    
    //===== One Page =====//
    var $root = $('html, body');
    $('.mnu-inn > ul > li a').on('click',function () {
        $root.animate({
            scrollTop: $($.attr(this, 'href')).offset().top
        }, 500);
        return false;
    });
});