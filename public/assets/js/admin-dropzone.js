(function ($) {
  "use strict";

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  // Dropzone initialization
  Dropzone.options.myDropzone = {
    acceptedFiles: '.png, .jpg, .jpeg',
    url: storeUrl,
    success: function (file, response) {
      $("#sliders").append(`<input type="hidden" name="slider_images[]" id="slider${response.file_id}" value="${response.file_id}">`);

      // Create the remove button
      var removeButton = Dropzone.createElement("<button class='rmv-btn'><i class='fa fa-times'></i></button>");
      var chkBox = Dropzone.createElement(`<div><input class='form-check-input customRadio' value='${response.file_id}' type='radio' name='flexRadioDefault' id='chkbo'><label class='customRadiolabel'> Set  cover</label></div>`);
      // Capture the Dropzone instance as closure.
      var _this = this;
      // Listen to the click event
      removeButton.addEventListener("click", function (e) {
        // Make sure the button click doesn't submit the form:
        e.preventDefault();
        e.stopPropagation();

        _this.removeFile(file);

        rmvimg(response.file_id);
      });

      // Add the button to the file preview element.
      file.previewElement.appendChild(removeButton);
      file.previewElement.appendChild(chkBox);

      if (typeof response.error != 'undefined') {
        if (typeof response.file != 'undefined') {
          document.getElementById('errpreimg').innerHTML = response.file[0];
        }
      }
    }
  };

  function rmvimg(fileid) {
    // If you want to the delete the file on the server as well,
    // you can do the AJAX request here.

    $.ajax({
      url: removeUrl,
      type: 'POST',
      data: {
        fileid: fileid
      },
      success: function (data) {
        $("#slider" + fileid).remove();
      }
    });

  }
$(document).on('click', '.customRadio', function () {

$("#defaultImg").val($(this).val());
 });
  //remove existing images
  $(document).on('click', '.rmvbtndb', function () {
    let indb = $(this).data('indb');
    $(".request-loader").addClass("show");
    $.ajax({
      url: rmvdbUrl,
      type: 'POST',
      data: {
        fileid: indb
      },
      success: function (data) {
        $(".request-loader").removeClass("show");
        var content = {};
        if (data == 'false') {
          content.message = "You can't delete all images.!!";
          content.title = 'Warning';
          var type = 'warning';
        } else {
          $("#trdb" + indb).remove();
          content.message = 'Slider image deleted successfully!';
          content.title = 'Success';
          var type = 'success';
        }

        content.icon = 'fa fa-bell';

        $.notify(content, {
          type: type,
          placement: {
            from: 'top',
            align: 'right'
          },
          showProgressbar: true,
          time: 1000,
          delay: 4000
        });
      }
    });
  });
})(jQuery);
