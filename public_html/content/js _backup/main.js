$(function () {
    'use strict';
    var swiper = new Swiper('.swip-slider', {
        effect: 'coverflow',
        grabCursor: true,
        centeredSlides: true,
        spaceBetween: -30,
        initialSlide: 2,
        coverflowEffect: {
            rotate: 50,
            stretch: 0,
            depth: 150,
            modifier: 1,
            slideShadows: true,
        },
        breakpoints: {
           0: {
                 slidesPerView: 1,
            },
            480: {
                 slidesPerView: 1
            },
            768: {
                slidesPerView: 1
            },
            1200: {
               slidesPerView: 3
            }
        },
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            dynamicBullets: true,
        }
    });  
    

})
