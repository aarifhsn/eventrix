(function ($) {
  "use strict";
  $(document).ready(function () {
    $("form").attr("autocomplete", "off");
    $("input, textarea").attr("autocomplete", "off");

    $(".number-counters").appear(function () {
      $(".number-counters [data-to]").each(function () {
        var e = $(this).attr("data-to");
        $(this).delay(6e3).countTo({
          from: 1,
          to: e,
          speed: 3000,
          refreshInterval: 50,
        });
      });
    });

    $("#sponsor-section .owl-carousel").owlCarousel({
      items: 5,
      loop: true,
      dots: false,
      nav: false,
      margin: 30,
      stagePadding: 25,
      autoplay: true,
      smartSpeed: 1200,
      responsiveClass: true,
      responsive: {
        0: {
          items: 1,
          nav: true,
        },
        480: {
          items: 2,
        },
        768: {
          items: 3,
        },
        992: {
          items: 5,
        },
      },
    });

    $("#speakers .owl-carousel").owlCarousel({
      items: 4,
      loop: true,
      dots: false,
      nav: false,
      margin: 30,
      stagePadding: 25,
      autoplay: true,
      smartSpeed: 1200,
      responsiveClass: true,
      responsive: {
        0: {
          items: 1,
          nav: true,
        },
        480: {
          items: 2,
        },
        768: {
          items: 3,
        },
        992: {
          items: 4,
        },
      },
    });

    $("#testimonial-slider").owlCarousel({
      items: 2,
      loop: true,
      dots: false,
      nav: false,
      margin: 50,
      stagePadding: 25,
      autoplay: true,
      smartSpeed: 1200,
      responsiveClass: true,
      responsive: {
        0: {
          items: 1,
          nav: true,
        },
        480: {
          items: 1,
        },
        768: {
          items: 2,
        },
        992: {
          items: 2,
        },
      },
    });

    $(".gallery_img").magnificPopup({
      type: "image",
      gallery: {
        enabled: true,
      },
    });

    $(".video-button").magnificPopup({
      type: "iframe",
      gallery: {
        enabled: true,
      },
    });

    $(function () {
      $("#navContent .dropdown").hover(
        function () {
          $("#dropmenu.dropdown-menu", this).stop(true, true).fadeIn("fast");
          $(this).toggleClass("open");
        },
        function () {
          $("#dropmenu.dropdown-menu", this).stop(true, true).fadeOut("fast");
          $(this).toggleClass("open");
        }
      );
    });

    $(function () {
      $('[data-toggle="tooltip"]').tooltip();
    });
    $(function () {
      $('[data-toggle="popover"]').popover();
    });
  });
})(jQuery);
