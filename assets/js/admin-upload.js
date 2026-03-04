/**
 * Laererliv — Mediebibliotek-velger for CPT-er i admin
 * Nedlastning: Filvelger med auto-utfylling av URL, filtype og filstørrelse
 * App: Ikonvelger med forhåndsvisning
 */
(function ($) {
  'use strict';

  $(function () {

    /* ============================
     * NEDLASTNING — Filvelger
     * ============================ */
    var dlFrame;

    $('#nedlastning_velg_fil').on('click', function (e) {
      e.preventDefault();

      if (dlFrame) {
        dlFrame.open();
        return;
      }

      dlFrame = wp.media({
        title: 'Velg eller last opp fil',
        button: { text: 'Bruk denne filen' },
        multiple: false,
      });

      dlFrame.on('select', function () {
        var attachment = dlFrame.state().get('selection').first().toJSON();

        // Sett URL
        $('#nedlastning_fil_url').val(attachment.url);

        // Sett filtype fra filnavn
        var ext = attachment.url.split('.').pop().toLowerCase();
        $('#nedlastning_filtype').val(ext.toUpperCase());

        // Sett filstørrelse
        if (attachment.filesizeInBytes) {
          var bytes = attachment.filesizeInBytes;
          var size;
          if (bytes >= 1048576) {
            size = (bytes / 1048576).toFixed(1) + ' MB';
          } else {
            size = Math.round(bytes / 1024) + ' KB';
          }
          $('#nedlastning_filstr').val(size);
        } else if (attachment.filesizeHumanReadable) {
          $('#nedlastning_filstr').val(attachment.filesizeHumanReadable);
        }
      });

      dlFrame.open();
    });

    /* ============================
     * APP — Ikonvelger
     * ============================ */
    var appFrame;

    $('#app_velg_ikon').on('click', function (e) {
      e.preventDefault();

      if (appFrame) {
        appFrame.open();
        return;
      }

      appFrame = wp.media({
        title: 'Velg ikon/bilde for appen',
        button: { text: 'Bruk dette bildet' },
        multiple: false,
        library: { type: 'image' },
      });

      appFrame.on('select', function () {
        var attachment = appFrame.state().get('selection').first().toJSON();
        var imgUrl = attachment.sizes && attachment.sizes.thumbnail
          ? attachment.sizes.thumbnail.url
          : attachment.url;

        $('#app_ikon_url').val(attachment.url);
        $('#app_ikon_img').attr('src', imgUrl);
        $('#app_ikon_preview').show();
        $('#app_fjern_ikon').show();
      });

      appFrame.open();
    });

    $('#app_fjern_ikon').on('click', function (e) {
      e.preventDefault();
      $('#app_ikon_url').val('');
      $('#app_ikon_preview').hide();
      $(this).hide();
    });

  });
})(jQuery);
