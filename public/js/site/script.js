jQuery(document).ready(function ($) {
    'use strict';

    //===== Dropdown Anmiation =====// 
    var drop = $('.tp-scil > ul > li,.mnu-inn > ul > li');
    $('.tp-scil > ul,.mnu-inn > ul').each(function () {
        var delay = 50;
        $(this).find(drop).each(function () {
            $(this).css({transitionDelay: delay + 'ms'});
            delay += 50;
        });
    });

    //===== Side Panel =====//
    $(".sidepanel > span").on('click', function () {
        $(".sidepanel").toggleClass("show");
        $(this).toggleClass('spin');
        return false;
    });

    //===== Color Picker =====*/
    $('.color-picker a').on("click", function () {
        $('.color-picker a').removeClass("applied");
        $(this).addClass("applied");
        return false;
    });

    //===== Sticky Headers =====// 
    $(window).scroll(function () {
        var scroll = $(window).scrollTop();
        if (scroll >= 10) {
            $(".stick").addClass("sticky");
        } else {
            $(".stick").removeClass("sticky");
        }
    });
    var mnu_hght = $("header").height();
    if ($("header").hasClass("stick")) {
        $(".web-lyut").css({"padding-top": mnu_hght});
    } else {
        $(".web-lyut").css({"padding-top": "0"});
    }

    //===== About Architecture =====*/
    $('.abu-gal li').each(function () {
        $(".abu-gal li").on('mouseenter', function () {
            $(this).parent().parent().parent().find('.abu-gal li').removeClass("active");
            $(this).addClass("active");
        });
    });

    //===== Top Bar Social =====//
    $('.tp-shr-btn').on('click', function () {
        $(this).parent().toggleClass('active');
    });

    //===== Contact Popup =====//
    $('.cnt-btns .blk-btn').on('click', function () {
        $('.hrs-wrp').addClass('active');
        return false;
    });
    $('.hrs-cls').on('click', function () {
        $('.hrs-wrp').removeClass('active');
        return false;
    });

    //===== Header Style 1 =====//
    $('.mnu-btn').on('click', function () {
        $('.mnu-wrp').addClass('active');
        return false;
    });
    $('.mnu-cls-btn').on('click', function () {
        $('.mnu-wrp').removeClass('active');
        return false;
    });

    //===== Sidebar =====//
    $('.sdbr-btn').on('click', function () {
        $('.sdbr').addClass('active');
        return false;
    });
    $('.sdbr-cls').on('click', function () {
        $('.sdbr').removeClass('active');
        return false;
    });

    //===== Responsive Header =====//
    $('.rspn-mnu-btn').on('click', function () {
        $('.rsnp-mnu').addClass('active');
        return false;
    });
    $('.rspn-mnu-cls').on('click', function () {
        $('.rsnp-mnu').removeClass('active');
        return false;
    });
    $('.rsnp-mnu li.menu-item-has-children > a').on('click', function () {
        $(this).parent().siblings().children('ul').slideUp();
        $(this).parent().siblings().removeClass('active');
        $(this).parent().children('ul').slideToggle();
        $(this).parent().toggleClass('active');
        return false;
    });

    //===== Select2 =====//
    if ($.isFunction($.fn.select2)) {
        $('select').select2();
    }

    //===== Scroll Bar =====//
    if ($.isFunction($.fn.perfectScrollbar)) {
        $(".rsnp-mnu > ul, .sdbr").perfectScrollbar();
    }

    //===== LightBox =====//
    if ($.isFunction($.fn.poptrox)) {
        var foo = $('.lgtbx');
        foo.poptrox({usePopupCaption: true, usePopupNav: true});
    }

    //===== Counter Up =====//
    if ($.isFunction($.fn.counterUp)) {
        $('.fun-itm strong').counterUp({
            delay: 10,
            time: 1000
        });
    }

    //===== Parallax =====//
    if ($.isFunction($.fn.parallax)) {
        $('.prlx').parallax();
    }

    //===== Responsive Contact =====//
    $('.rspn-cnt li').each(function () {
        $(".rspn-cnt li").on('click', function () {
            $(".rspn-cnt li").removeClass("active");
            $(this).addClass("active");
        });
    });

    //===== Ajax Contact Form =====//
    $('#contactform').submit(function () {
        var action = $(this).attr('action');
        var msg = $('#message');
        $(msg).hide();
        var data = 'name=' + $('#name').val() + '&email=' + $('#email').val() + '&phone=' + $('#phone').val() + '&comments=' + $('#comments').val() + 'verify=' + $('#verify').val() + 'captcha=' + $(".g-recaptcha-response").val();
        $.ajax({
            type: 'POST',
            url: action,
            data: data,
            beforeSend: function () {
                $('#submit').attr('disabled', true);
                $('img.loader').fadeIn('slow');
            },
            success: function (data) {
                $('#submit').attr('disabled', false);
                $('img.loader').fadeOut('slow');
                $(msg).empty();
                $(msg).html(data);
                $('#message').slideDown('slow');
                if (data.indexOf('success') > 0) {
                    $('#contactform').slideUp('slow');
                }
            }
        });
        return false;
    });

    //===== Owl Carousel =====//
    if ($.isFunction($.fn.owlCarousel)) {
        //===== Testimonials Carousel =====//
        $('.test-car').owlCarousel({
            autoplay: true,
            smartSpeed: 800,
            loop: true,
            items: 1,
            dots: false,
            slideSpeed: 2000,
            nav: true,
            margin: 0,
            animateIn: 'fadeInDown',
            animateOut: 'fadeOutDown'
        });

        //===== Recent Post Carousel =====//
        $('.rcnt-pst-car').owlCarousel({
            autoplay: true,
            smartSpeed: 500,
            loop: true,
            items: 2,
            dots: false,
            slideSpeed: 2000,
            nav: true,
            margin: 30,
            responsiveClass: true,
            responsive: {
                0: {nav: false, items: 1},
                767: {nav: false, items: 1},
                980: {items: 1},
                1200: {items: 2}
            }
        });

        //===== Clients Logo Carousel =====//
        $('.clnt-car').owlCarousel({
            autoplay: true,
            smartSpeed: 600,
            loop: true,
            items: 5,
            dots: false,
            slideSpeed: 2500,
            nav: true,
            margin: 30,
            responsiveClass: true,
            responsive: {
                0: {nav: false, items: 2},
                767: {nav: false, items: 3},
                980: {items: 4},
                1200: {items: 5}
            }
        });

        //===== Work Flow Carousel =====//
        $('.wrk-flw-car').owlCarousel({
            autoplay: false,
            smartSpeed: 600,
            loop: false,
            items: 3,
            dots: false,
            slideSpeed: 2000,
            nav: true,
            margin: 30,
            responsiveClass: true,
            responsive: {
                0: {items: 1, nav: false},
                767: {items: 1},
                980: {items: 2},
                1200: {items: 3}
            }
        });

        //===== Text Carousel =====//
        $('.txt-car-inn').owlCarousel({
            autoplay: true,
            smartSpeed: 400,
            loop: true,
            items: 1,
            dots: false,
            slideSpeed: 2000,
            nav: true,
            margin: 0,
            animateIn: 'fadeIn',
            animateOut: 'fadeOut',
            autoHeight: true,
            responsiveClass: true,
            responsive: {
                0: {nav: false, items: 1},
                767: {nav: false, items: 1},
                980: {items: 1},
                1200: {items: 1}
            }
        });
    }

    //===== Circle Progressbar =====//
    if ($.isFunction($.fn.circliful)) {
        //=== Progressbar 1 ===//
        $('#prg-br1').circliful();

        //=== Progressbar 2 ===//
        $('#prg-br2').circliful();

        //=== Progressbar 3 ===//
        $('#prg-br3').circliful();

        //=== Progressbar 4 ===//
        $('#prg-br4').circliful();

        //=== Progressbar 5 ===//
        $('#prg-br5').circliful();
    }

    //===== Slick Carousel =====//
    if ($.isFunction($.fn.slick)) {
    }

    //===== Swiper Carousel =====//
    if (typeof Swiper != 'undefined') {
        //===== Footer Gallery Carousel =====//
        var swiper = new Swiper('.ftr-glry-car', {
            pagination: '.ftr-glry-pgn',
            paginationClickable: true,
            paginationBulletRender: function (index, className) {
                return '<span class="' + className + '">' + (index + 1) + '</span>';
            },
            loop: true,
        });
    }

    //===== Contact Form =====//
    $('#submit').on('click', function () {
        var form = $(this).parent().parent().parent();
        var action = $(form).attr('action');
        var msg = $(form).prev();
        var _name = $(form).find('input[name="name"]').val();
        var _email = $(form).find('input[name="email"]').val();
        var _subject = $(form).find('input[name="subject"]').val();
        var _description = $(form).find('textarea[name="description"]').val();
        var data = 'name=' + _name + '&=email=' + _email + '&subject=' + _subject + '&description=' + _description;
        /*$(msg).empty();
         $(this)
         .after('<img src="images/ajax-loader.gif" class="loader" />')
         .attr('disabled','disabled');*/

        jQuery.ajax({
            type: "post",
            url: wpd_object.ajax_url,
            data: data,
            beforeSend: function () {

            },
            success: function (data) {
                $(msg).html(data);
                $(msg).slideDown('slow');
                //$(form + 'img.loader').fadeOut('slow',function(){$(this).remove()});
                $(this).removeAttr('disabled');
                if (data.match('success') !== null)
                    $(form).slideUp('slow');
            }
        });
        return false;
    });
});/*===== Document Ready Function Ends Here =====*/

jQuery(window).on('load', function () {
    'use strict';

    $('.loader-wrapper').fadeOut();
});