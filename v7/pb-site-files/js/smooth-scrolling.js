// refrence site working example https://mfo.dev99.net/


$(document).ready(function() {
    //Smooth scrolling when click to nav
    $('#primary-menu a[href^="#"], .my-footer-links a[href^="#"]').click(function(e) {
        e.preventDefault();
        var curLink = $(this);
        console.dir(curLink);
        var scrollPoint = $(curLink.attr('href')).position().top - 65;
        if ( curLink[0].hash.toString() != "#section-home")
            scrollPoint = scrollPoint + 700;

        $('body,html').animate({
            scrollTop: scrollPoint
        }, 1000);
        $(this).parent().addClass('active').siblings().removeClass('active');
    });

    $(window).scroll(function() {
        onScrollHandle();
    });

    function onScrollHandle() {

        //Get current scroll position
        var currentScrollPos = $(document).scrollTop();

        //Iterate through all node
        $('.site-nav > ul > li:not(.nav-btn) a').each(function() {
            var curLink = $(this);
            var refElem = $(curLink.attr('href'));
            //Compare the value of current position and the every section position in each scroll
            if (refElem.position().top - 67 <= currentScrollPos && refElem.position().top - 67 + refElem.height() > currentScrollPos) {
                //Remove class active in all nav
                $('.site-nav > ul > li').removeClass("active");
                //Add class active
                curLink.parent().addClass("active");
            } else {
                curLink.parent().removeClass("active");
            }
        });
    }
});