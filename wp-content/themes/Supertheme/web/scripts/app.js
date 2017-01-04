jQuery(function() {
    jQuery(document).foundation();
    jQuery('.fancybox').fancybox({
        maxWidth: 420,
        scrolling: "no",
        fitToView: false
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
    jQuery('.reviews-slider').slick({
        slidesToShow: 1,
        dots: true,
        arrows: false,
        adaptiveHeight: true
    });

    jQuery('.scroll-top').on("click", function (e) {
        console.log("scrolling to top");
        jQuery("html, body").animate({scrollTop: 0}, "slow");
        e.preventDefault();
        return false;
    });
    jQuery('.insurance > a').on("click", function (e) {
        console.log("scrolling to insurance section");
        if(jQuery("#insurance").length) {
            jQuery("html, body").animate({scrollTop: jQuery('#insurance').offset().top - 160}, "slow");
            e.preventDefault();
            return false;
        }
    });
});
