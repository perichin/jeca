(function($) {

    $(document).ready(function() {

        /*-----------------------------------------------------------------------------------*/
        /* RESPONSIVE NAVIGATION
        /*-----------------------------------------------------------------------------------*/

        $('#nav-open-btn').sidr({
            name: 'sidr-existing-content',
            source: '#nav',
            speed: 100
        });

        $('#nav-open-btn').on('touchstart click', function(e) {
            e.stopPropagation();
            e.preventDefault();
            if (!$(this).hasClass('nav-open')) {
                $.sidr('open', 'sidr-existing-content');
                $(this).addClass('nav-open');
            } else {
                $.sidr('close', 'sidr-existing-content');
                $(this).removeClass('nav-open');
            }
        });
        $('.sidr ul li').each(function() {
            if ($(this).hasClass('sidr-class-menu-item-has-children')) {
                $(this).append('<span class="thr_menu_parent fa fa-angle-down"></span>')
            }
        });

        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            $('.thr_menu_parent').on('touchstart', function(e) {
                $(this).prev().toggle();
                $(this).parent().toggleClass('sidr-class-current_page_item');
            });

            $('.soc_sharing').on('click', function() {
                $(this).toggleClass('soc_active');
            });

        } else {
            $('.thr_menu_parent').on('click', function(e) {
                $(this).prev().toggle();
                $(this).parent().toggleClass('sidr-class-current_page_item');
            });
        }

        $('#main_content').on('click', function(e) {
            if ($('body').hasClass('sidr-open')) {
                $.sidr('close', 'sidr-existing-content');
            }
        });

        /*-----------------------------------------------------------------------------------*/
        /*RESPONSIVE VIDEO CONTROL
        /*-----------------------------------------------------------------------------------*/

        if ($.isFunction($.fn.fitVids)) {
            $(".entry-content").fitVids();
            $("#subheader_box").fitVids();
        }

        /*-----------------------------------------------------------------------------------*/
        /* SEARCH BUTTON
        /*-----------------------------------------------------------------------------------*/

        $('#search_header').on('click', function(e) {
            e.preventDefault();
            $(this).parent().show();

            if ($('.search_header_form').is(':visible')) {

                $('.nav-menu li:not(#search_header_wrap)').animate({
                    opacity: 1
                }, 100);
                $('.search_header_form').toggle('fast');

            } else {

                $('.search_header_form').slideToggle('fast');
                $('.nav-menu li:not(#search_header_wrap)').animate({
                    opacity: 0
                }, 100);

            }
            $(this).next().find('.search_input').focus();
            $('#search_header i').toggleClass('fa-times', 'fa-search');
        });

        $(".search_input").keyup(function() {
            var input_val = $(this).attr('value');
            $('.search_input').attr('value', input_val);
        });

        /*-----------------------------------------------------------------------------------*/
        /* MATCH HEIGHT FOR LAYOUTS D AND C
        /*-----------------------------------------------------------------------------------*/
        $('.layout_c.post, .layout_d.post, .layout_c.page, .layout_d.page').matchHeight();


        /*-----------------------------------------------------------------------------------*/
        /* PAGE PROGRESS BAR
        /*-----------------------------------------------------------------------------------*/

        $(window).scroll(function() {
            var s = $(window).scrollTop(),
                d = $('.entry-content').height(),
                c = $(window).height();
            scrollPercent = (s / (d - c)) * 100;
            var position = scrollPercent;

            $(".page-progress span").css('width', position + '%');
        });

        /*-----------------------------------------------------------------------------------*/
        /*  FEATURED AREA HOVER
        /*-----------------------------------------------------------------------------------*/

        $(".featured_element").hover(
            function() {
                var height_title = $(this).find('.featured_title_over').outerHeight();
                var title_hidden = $(this).find('.featured_excerpt').height();
                $(this).find('.featured_excerpt').animate({
                    opacity: 1
                }, 100);
                $(this).find('.featured_title_over').animate({
                    bottom: title_hidden
                }, 100);

            },
            function() {

                var height_title_2 = $(this).find('.featured_title_over').height();
                var title_hidden_2 = $(this).find('.featured_excerpt').height();

                $(this).find('.featured_title_over').animate({
                    bottom: 0
                }, 100);

                $(this).find('.featured_excerpt').animate({
                    opacity: 0
                }, 100);
            });

        $(".sub-menu").hover(
            function() {
                $('.featured_element').addClass('thr_stop_hover');

            },
            function() {
                $('.featured_element').removeClass('thr_stop_hover');
            });

        $("#nav li").hover(
            function() {
                $(this).children('.sub-menu').stop(true, true).slideDown(200);
            },
            function() {
                $(this).children('.sub-menu').stop(true, true).slideUp(200);
            });

        /*-----------------------------------------------------------------------------------*/
        /* BACK TO TOP
        /*-----------------------------------------------------------------------------------*/
        $(window).scroll(function() {
            if ($(this).scrollTop() > 100) {
                $('#back-top').fadeIn();
            } else {
                $('#back-top').fadeOut();
            }
        });

        $('#back-top').click(function() {
            $('body,html').animate({
                scrollTop: 0
            }, 800);
            return false;
        });


        /*-----------------------------------------------------------------------------------*/
        /* STICKY SIDEBAR
        /*-----------------------------------------------------------------------------------*/

        function sticky_sidebar() {
            if ($(window).width() > 950) {
                if ($('.thr_sticky').length > 0) {
                    imagesLoaded(document.querySelector('#sidebar'), function(instance) {

                        if ($('.main_content_wrapper').height() > $('.sidebar').height()) {

                            var t = $('.thr_sticky').offset().top - 40;
                            var b = $('.footer_wrapper').outerHeight() + 40;

                            $(".thr_sticky").affix({
                                offset: {
                                    top: function() {
                                        return t;
                                    },
                                    bottom: function() {
                                        return ($(".thr_sticky").hasClass("affix-top")) ? 0 : b;
                                    }
                                }
                            });
                        }

                    });
                }
            }
        }

        sticky_sidebar();

        $(window).bind('resize', function() {
            if ($(window).width() < 950) {
                $(".thr_sticky").addClass('affix-responsive');
                if ($('.sidebar.left').length > 0) {
                    $('.sidebar.left').insertAfter('.main_content_wrapper');
                }

                if (thr_js_settings.logo_retina != "" && $("#header .logo_wrapper img").length > 0) {
                    var retina = window.devicePixelRatio > 1;
                    if (retina) {

                        var img_width = $('#header .logo_wrapper img').width();
                        var img_height = $('#header .logo_wrapper img').height();

                        $('#header .logo_wrapper img').css("width", img_width);
                        $('#header .logo_wrapper img').css("height", img_height);

                        $("#header .logo_wrapper img").attr("src", thr_js_settings.logo_retina);
                    }
                }

            } else {
                $(".thr_sticky").removeClass('affix-responsive');
            }


        });

        if ($(window).width() < 950) {
            if ($('.sidebar.left').length > 0) {
                $('.sidebar.left').insertAfter('.main_content_wrapper');
            }
        }

        /*-----------------------------------------------------------------------------------*/
        /* ARCHIVE TITLE ANIMATION
        /*-----------------------------------------------------------------------------------*/
        if ($('#archive_title h1').length) {
            var cat_h_w = $('#archive_title h1').width();
            $('.arch_line').animate({
                width: cat_h_w
            });
        }

        /*-----------------------------------------------------------------------------------*/
        /* GALLERY AND IMAGE POSTS
        /*-----------------------------------------------------------------------------------*/

        if (thr_js_settings.use_lightbox) {

            $('.thr_image_format').magnificPopup({
                type: 'image',
                gallery: {
                    enabled: false
                },
                image: {
                    titleSrc: function(item) {
                        var $caption = item.el.closest('.entry-image').find('.photo_caption');
                        if ($caption != 'undefined') {
                            return $caption.text();
                        }
                        return '';
                    }
                }
            });

            $('.gallery').each(function() {
                $(this).find('.gallery-icon a').magnificPopup({
                    type: 'image',
                    gallery: {
                        enabled: true
                    },

                    image: {
                        titleSrc: function(item) {
                            var $caption = item.el.closest('.gallery-item').find('.gallery-caption');
                            if ($caption != 'undefined') {
                                return $caption.text();
                            }
                            return '';
                        }
                    }
                });
            });

        }

        if (thr_js_settings.use_lightbox_content) {
            $('a.thr-popup').magnificPopup({
                 type: 'image',
                 image: {
                     titleSrc: function(item) {
                         var $caption = item.el.next('.wp-caption-text');
                         if ($caption != 'undefined') {
                             return $caption.text();
                         }
                         return '';
                     }
                 }
            });

           $('.thr-popup img').each(function() { 
                if($(this).hasClass('alignright')){
                    $(this).parent().addClass('alignright');
                } else if($(this).hasClass('alignleft')){
                    $(this).parent().addClass('alignleft');
                } else if($(this).hasClass('aligncenter')){
                    $(this).parent().addClass('aligncenter');
                }
           });

        }

        /*-----------------------------------------------------------------------------------*/
        /* Load some images nicely
        /*-----------------------------------------------------------------------------------*/

        $('#featured_wrapper').imagesLoaded(function() {
            $('#featured_wrapper').animate({
                opacity: 1
            }, 300);
        });

        $('.entry-content .gallery').imagesLoaded(function() {
            $('.gallery-item').animate({
                opacity: 1
            }, 500);
        });

        $('.single_b .entry-image').imagesLoaded(function() {
            $('.single_b .entry-image').animate({
                opacity: 1
            }, 500);
        });

        $('.body_bg_img').imagesLoaded(function() {
            $('.body_bg_img').animate({
                opacity: 1
            }, 500);
        });



        /*-----------------------------------------------------------------------------------*/
        /* SCROLL TO COMMENTS
        /*-----------------------------------------------------------------------------------*/
        $('body.single .entry-meta .comments a, body.single a.button_respond').click(function(e) {
            e.preventDefault();
            var target = this.hash,
                $target = $(target);

            $('html, body').stop().animate({
                'scrollTop': $target.offset().top
            }, 900, 'swing', function() {
                window.location.hash = target;
            });
        });

        /*-----------------------------------------------------------------------------------*/
        /* Open popup on post share links
        /*-----------------------------------------------------------------------------------*/

        $('body').on('click', 'ul.thr_share_items a', function(e) {
            e.preventDefault();
            var data = $(this).attr('data-url');
            thr_social_share(data);
        });

        function thr_social_share(data) {
            window.open(data, "Share", "height=500,width=760,resizable=0,toolbar=0,menubar=0,status=0,location=0,scrollbars=0");
        }

        /*-----------------------------------------------------------------------------------*/
        /* STICKY HEADER
        /*-----------------------------------------------------------------------------------*/
        if (thr_js_settings.sticky_header) {

            var site_logo = $('.logo_wrapper');
            var site_nav = $('.main_navigation');
            var site_responsive_nav = $('#nav-open-btn');
            site_logo.clone(true).appendTo("#sticky_header .content_wrapper");
            site_nav.clone(true, true).appendTo("#sticky_header .content_wrapper");
            site_responsive_nav.clone(true).appendTo("#sticky_header .content_wrapper");

            var $site_title = $("#sticky_header .site-title");
            $site_title.replaceWith(function() {
                return $('<div/>', {
                    class: 'site-title',
                    html: this.innerHTML
                });
            });


            if (thr_js_settings.sticky_header_logo != "" && $("#sticky_header .logo_wrapper img").length > 0) {
                var retina = window.devicePixelRatio > 1;
                if (retina && thr_js_settings.sticky_header_logo_retina) {
                    $("#sticky_header .logo_wrapper img").attr("src", thr_js_settings.sticky_header_logo_retina);
                } else {
                    $("#sticky_header .logo_wrapper img").attr("src", thr_js_settings.sticky_header_logo);
                }
            }

            if ($('#header').length) {

                var sticky_header_top = $('#header').offset().top + parseInt(thr_js_settings.sticky_header_offset);

                $(window).scroll(function() {
                    if ($(window).scrollTop() > sticky_header_top) {
                        $('#sticky_header').addClass('header-is-sticky');

                        if ($('#header .search_header_form').is(':visible')) {
                            $('#sticky_header .search_header_form').show();
                            $('#sticky_header .search_input').focus();
                        }

                    } else {
                        $('#sticky_header').removeClass('header-is-sticky');
                    }
                });

            }
        }

        if ($('.single .meta-share').length > 0) {
            var header_height = $('.header').height();
            var window_height = $(window).height();
            var fix_height = (window_height - header_height) / 2;
            $('.single .meta-share').css('top', fix_height + 'px');
        }

    }); //END DOCUMENT READY


    $(window).load(function() {

        /*-----------------------------------------------------------------------------------*/
        /* RETINA LOGO
        /*-----------------------------------------------------------------------------------*/

        if (thr_js_settings.logo_retina != "" && $("#header .logo_wrapper img").length > 0) {
            var retina = window.devicePixelRatio > 1;
            if (retina) {

                var img_width = $('#header .logo_wrapper img').width();
                var img_height = $('#header .logo_wrapper img').height();

                $('#header .logo_wrapper img').css("width", img_width);
                $('#header .logo_wrapper img').css("height", img_height);

                $("#header .logo_wrapper img").attr("src", thr_js_settings.logo_retina);
            }
        }

    });


})(jQuery);