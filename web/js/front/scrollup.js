$(document).ready(function() {

    /*-----------------------------------------------------
    :: SCROLLUP
    -----------------------------------------------------*/
    $(window).scroll(function(){
        if ($(this).scrollTop() > 120) {
            $('.scrollup').fadeIn();
        } else {
            $('.scrollup').fadeOut();
        }
    });

    $('.scrollup').click(function(){
        $("html, body").animate({
            scrollTop: 0
        }, 600);
        return false;
    });

});