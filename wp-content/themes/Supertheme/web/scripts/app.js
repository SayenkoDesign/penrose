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
});
