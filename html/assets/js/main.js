$(document).ready(function () {


    // hightlights book 
    var highlights_book = new Swiper(".highlights_book", {
        slidesPerView: 4,
        spaceBetween: 30,
        navigation: false,
        autoplay: true,
        pagination: {
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        breakpoints: {
            '0': {
                slidesPerView: 1,
                spaceBetween: 40,
            },
            '480': {
                slidesPerView: 2,
                spaceBetween: 40,
            },
            '768': {
                slidesPerView: 3,
                spaceBetween: 50,
            },
            '992': {
                slidesPerView: 4,
                spaceBetween: 50,
            },
        },
    });

    //  show password 
    $(".toggle-password").click(function () {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });

    //  show password 
    $(".confirm_pass").click(function () {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });

})  // end document