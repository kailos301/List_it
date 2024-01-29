function disableWithUrl() {
  'use strict';

  $('#withUrl input').removeClass('item-menu');
  $('#withUrl select').removeClass('item-menu');
}

function enableWithUrl() {
  'use strict';

  $('#withUrl input').addClass('item-menu');
  $('#withUrl select').addClass('item-menu');
}

function disableWithoutUrl() {
  'use strict';

  $('#withoutUrl input').removeClass('item-menu');
  $('#withoutUrl select').removeClass('item-menu');
}

function enableWithoutUrl() {
  'use strict';

  $('#withoutUrl input').addClass('item-menu');
  $('#withoutUrl select').addClass('item-menu');
}

(function ($) {
  'use strict';

  // icon picker options
  var iconPickerOptions = {
    searchText: 'Buscar...', 
    labelHeader: '{0}/{1}'
  };

  // sortable list options
  var sortableListOptions = {
    placeholderCss: { 'background-color': '#cccccc' }
  };

  var editor = new MenuEditor('myMenuEditor', {
    listOptions: sortableListOptions,
    iconPicker: iconPickerOptions,
    maxLevel: 1
  });

  editor.setForm($('#menu-builder-form'));
  editor.setUpdateButton($('#btn-update'));

  editor.setData(allMenus);

  $('#btn-menu-update').on('click', function () {
    var str = editor.getString();
    var fd = new FormData();
    fd.append('str', str);
    fd.append('languageId', langId);

    $.ajax({
      url: menuBuilderUrl,
      type: 'POST',
      data: fd,
      contentType: false,
      processData: false,
      dataType: 'json',
      success: function (data) {
        var content = {};

        content.message = data.message;
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
      }
    });
  });

  // when menu is choosen from built-in menu list
  $('.addToMenus').on('click', function (e) {
    e.preventDefault();
    disableWithUrl();

    $("input[name='type']").val($(this).data('type'));
    $("#withoutUrl input[name='text']").val($(this).data('text'));
    $("#withoutUrl input[name='target']").val('_self');

    editor.add();
    enableWithUrl();
  });

  $('#btn-add').on('click', function () {
    disableWithoutUrl();

    $("input[name='type']").val('custom');

    editor.add();
    enableWithoutUrl();
  });

  $('#btn-update').on('click', function () {
    disableWithoutUrl();
    editor.update();
    enableWithoutUrl();
  });
})(jQuery);
