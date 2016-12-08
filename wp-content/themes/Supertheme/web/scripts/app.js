jQuery(function() {
    jQuery(document).foundation();
    jQuery('.fancybox').fancybox({
        helpers: {
            media : {},
            overlay: {
                locked: false
            }
        }
    });
    jQuery('.fancybox-media').fancybox({
        openEffect  : 'none',
        closeEffect : 'none',
        helpers : {
            media : {}
        }
    });
    jQuery('.slick').slick();
    jQuery('.partners-slider').slick({
        arrows: true,
        slidesToShow: 4,
        responsive: [{
            breakpoint: 700,
            settings: {
                slidesToShow: 1
            },
            breakpoint: 1024,
            settings: {
                slidesToShow: 2
            }
        }]
    });
});
