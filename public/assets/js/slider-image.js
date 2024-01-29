'use strict';
Dropzone.options.sliderDropzone = {
  paramName: 'slider_image',
  url: imgUpUrl,
  method: 'post',
  success: function (file, response) {
    // remove error message if exist
    $('#err_slider_image').text('');

    $('#slider-image-id').append(`<input type="hidden" id="img-${response.uniqueName}" name="slider_images[]" value="${response.uniqueName}">`);

    // create remove button
    const rmvBtn = Dropzone.createElement("<button class='rmv-btn'><i class='fa fa-times'></i></button>");

    // capture the dropzone instance as closure
    let _this = this;

    // bind an event to the remove button
    rmvBtn.addEventListener('click', function (event) {
      // make sure the button click event doesn't submit the form
      event.preventDefault();
      event.stopPropagation();

      // remove image from dropzone preview
      _this.removeFile(file);

      // remove video from storage
      rmvImg(response.uniqueName);
    });

    // add the remove button to the file preview element
    file.previewElement.appendChild(rmvBtn);
  },
  error: function (file, message) {
    $('#err_slider_image').text(message.error.slider_image[0]);

    // create remove button
    const rmvBtn = Dropzone.createElement("<button class='rmv-btn'><i class='fa fa-times'></i></button>");

    // capture the dropzone instance as closure
    let _this = this;

    // bind an event to the remove button
    rmvBtn.addEventListener('click', function (event) {
      // make sure the button click event doesn't submit the form
      event.preventDefault();
      event.stopPropagation();

      // remove video from dropzone preview
      _this.removeFile(file);
    });

    // add the remove button to the file preview element
    file.previewElement.appendChild(rmvBtn);
  }
};

function rmvImg(unqName) {
  $.ajax({
    url: imgRmvUrl,
    type: 'POST',
    data: { 'imageName': unqName },
    success: function (response) {
      const image = document.getElementById('img-' + unqName);
      image.remove();
    },
    error: function (response) {
    }
  });
}

function rmvStoredImg(id, key) {
  $.ajax({
    url: imgDetachUrl,
    type: 'POST',
    data: { 'id': id, 'key': key },
    success: function (response) {
      $('#slider-image-' + key).remove();

      let content = {};

      content.message = response.message;
      content.title = 'Success';
      content.icon = 'fa fa-bell';

      $.notify(content, {
        type: 'success',
        placement: {
          from: 'top',
          align: 'right'
        },
        showProgressbar: true,
        time: 1000,
        delay: 4000
      });

      $('#reload-slider-div').load(location.href + ' #reload-slider-div');
    },
    error: function (response) {
      let content = {};

      content.message = response.responseJSON.message;
      content.title = 'Danger';
      content.icon = 'fa fa-bell';

      $.notify(content, {
        type: 'danger',
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
}
