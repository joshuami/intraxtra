/**
 * @file
 * Back to top button.
 *
 * Display back to top button at viewheight * 1.5.
 */
(function ($, Drupal) {
  "use strict";
  Drupal.behaviors.westyBackToTop = {
    attach: function (context, settings) {

      var timer;

      function toggleButton() {
        var button = $('#back-to-top');
        var scrollPosition = $(window).scrollTop();
        var limit = $(window).height() * 1.5;

        if (scrollPosition > limit) {
          button.removeClass('d-none');
        } else {
          button.addClass('d-none');
        }
      }


      $(window).resize(function () {
        if (timer) clearTimeout(timer);
        timer = setTimeout(function () {
          toggleButton();
        }, 100);
      });

      // Add a timer so it does not trigger multiple times on a single scroll.
      $(window).scroll(function () {
        if (timer) clearTimeout(timer);
        timer = setTimeout(function () {
          toggleButton();
        }, 100);
      });

      $(window).on("load", function () {
        toggleButton();
      });

    }
  };
})(jQuery, Drupal);
