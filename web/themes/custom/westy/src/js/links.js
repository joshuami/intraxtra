// Modify links in pages
(function ($, Drupal) {
  $(document).ready(function () {
    // Heading ID Inserter
    var lastH2ID = '';
    var cleanText = function (data) {
      // Remove all comments
      var headerText = data.replace(/(<!--.*?-->)|(<!--[\S\s]+?-->)|(<!--[\S\s]*?$)/g, "");
      // Strip HTML
      headerText = headerText.replace(/(<([^>]+)>)/ig, "").trim();
      // Remove line breaks
      headerText = headerText.replace(/(\r\n|\n|\r)/gm, "");
      // Lowercase
      headerText = headerText.toLowerCase();
      // Replace Spaces and Special Characters
      headerText = headerText.replace(/[^a-z0-9-\s]/gi, '').replace(/[_\s]/g, '-');

      return headerText;
    }

    $('body .page__content').find('h2, h3').each(function () {
      var header = $(this);
      var hasID = header.attr('id');
      var headerType = header.prop("tagName").toLowerCase();
      var hasContent = header.html();

      // Only add IDs to headers with no IDs
      if (!hasID && hasContent) {

        var headerText = cleanText(hasContent);

        if (headerType == 'h2') {
          lastH2ID = headerText;
        }

        if (headerType == 'h3') {

          if (lastH2ID) {
            headerText = lastH2ID + '-' + headerText;
          }
        }

        header.attr("id", headerText + '-section');
      }
    });

    var target = window.location.hash;

    if (target) {
      target = decodeURI(target);
      var cleanTarget = '#' + cleanText(target);
      var offset = 0;

      if ($(cleanTarget).length > 0) {
        window.location.hash = cleanTarget;
        $('html, body').animate({
          scrollTop: $(cleanTarget).offset().top - offset
        }, 100);
      }
      else if ($(cleanTarget + '-section').length > 0) {
        window.location.hash = cleanTarget + '-section';
        $('html, body').animate({
          scrollTop: $(cleanTarget + '-section').offset().top - offset
        }, 100);
      }
    }



    // BreadCrumb Expander
    $('#expand-breadcrumb').on("click", function () {
      $(this).parent().siblings('.hidden').removeClass('hidden');
      $(this).parent().remove();
    });

    // Google Translate Link Transform
    var getUrl = window.location;
    var isGoogleTranslate = getUrl.origin.indexOf(".translate.goog");

    if (isGoogleTranslate > 1) {
      var origURL = getUrl.origin.split(".translate.goog");
      origURL = origURL[0].replace(/-/g, ".").replace(/\.\./g, "-");
      $('#nav-translate-espanol').remove();
      $('#nav-translate').html('<a class="fw-bold btn btn-primary notranslate" href="' + origURL + getUrl.pathname + '">English</a>');
    }

    // Link Icon Insert
    $('body .page__content, body .page__side').find('a').each(function () {
      var link = $(this);
      var url = link.attr('href');
      var hasImage = link.find('img, svg, div, i.fal, span.fal').length;

      // Only add icons to a tags with a href value
      if (url && !hasImage) {
        if (url.match("^tel:")) {

          // Add +1 US code to phone numbers.
          var tel = url.split('tel:')[1];
          if (tel[0] != '+') {
            var baseTel = tel.split(',')[0].replace(/\D/g, "");
            if (baseTel.length == 10) {
              link.attr('href', 'tel:+1' + tel);
            }
          }
          link.prepend("<i class='fal fa-xs fa-phone pe-1'></i>");
        }
        else if (url.match("^http:") || url.match("^https:") || url.match("^//")) {
          if (!url.match("^https://www.washingtoncountyor.gov") && !url.match("^https://www-washingtoncountyor-gov")) {
            link.prepend("<i class='fal fa-xs fa-up-right-from-square pe-1'></i>");
          }
        }
        else if (url.match("^#")) {
          if (!link.hasClass('facets-soft-limit-link') && !link.hasClass('ckeditor-accordion-toggler')) {
            link.prepend("<i class='fal fa-xs fa-anchor pe-1'></i>");
          }
        }
      }
    });

    // Bootstrap Accordion Expander
    $('main').find('.accordion').each(function () {

      var accordion = $(this);
      var accordionItems = accordion.find('.accordion-item');

      if (accordionItems) {


        var itemTotal = accordionItems.length;

        if (itemTotal > 1) {

          accordion.prepend('<p class="text-end d-block mb-1"><a href="javascript:void(0)" class="toggle-accordion"></a></p>');
          accordion.delegate('a.toggle-accordion', 'click', function () {
            var toggleBtn = $(this);
            toggleBtn.toggleClass('active');

            if (toggleBtn.hasClass('active')) {
              accordion.find('button.accordion-button').removeClass('collapsed').attr('aria-expanded', 'true');
              accordion.find('div.accordion-collapse').addClass('show');
            }
            else {
              accordion.find('button.accordion-button').addClass('collapsed').attr('aria-expanded', 'false');
              accordion.find('div.accordion-collapse').removeClass('show');
            }
          });
        }
      }
    });

    // DT/DD Accordion Expander
    $('main').find('.ckeditor-accordion-container').each(function () {

      var accordion = $(this);
      var accordionItems = accordion.find('dl dt');

      if (accordionItems) {



        var itemTotal = accordionItems.length;

        if (itemTotal > 1) {

          accordion.prepend('<p class="text-end d-block mb-1"><a href="javascript:void(0)" class="toggle-accordion"></a></p>');
          accordion.delegate('a.toggle-accordion', 'click', function () {
            var toggleBtn = $(this);
            toggleBtn.toggleClass('active');

            if (toggleBtn.hasClass('active')) {
              accordion.find('dt').addClass('active');
              accordion.find('dd').addClass('active').css("display", "block");
            }
            else {
              accordion.find('dt').removeClass('active');
              accordion.find('dd').removeClass('active').css("display", "none");;
            }
          });
        }
      }
    });

  });
})(jQuery, Drupal);
