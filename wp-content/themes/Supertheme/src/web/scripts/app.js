// universal analytics
if(SP.Settings.AnalyticsID) {
    if (SP.Settings.UniversalAnalytics) {
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

        ga('create', SP.Settings.AnalyticsID, 'auto');
        ga('send', 'pageview');
    } else {
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', SP.Settings.AnalyticsID]);
        _gaq.push(['_trackPageview']);

        (function () {
            var ga = document.createElement('script');
            ga.type = 'text/javascript';
            ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(ga, s);
        })();
    }
}

jQuery('.scroll-top').on("click", function(e){
    jQuery("html, body").animate({ scrollTop: 0 }, "slow");
    e.preventDefault();
    return false;
});