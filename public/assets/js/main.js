$(window).on('load', function () {
    'use strict';

    //===== Popup
    if ($('.popup-wrapper').length > 0) {
        let $firstPopup = $('.popup-wrapper').eq(0);

        appearPopup($firstPopup);
    }
});

(function ($) {
    'use strict';

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // search post by category
    $('.post-category').on('click', function (e) {
        e.preventDefault();

        $('input[name="title"]').attr('disabled', true);

        let blogCategory = $(this).data('category_slug');

        $('#categoryKey').val(blogCategory);
        $('#form-submit-btn').trigger('click');
    });

    // uploaded image preview
    if ($('.upload').length > 0) {
        $('.upload').on('change', function (event) {
            let file = event.target.files[0];
            let reader = new FileReader();

            reader.onload = function (e) {
                $('.user-photo').attr('src', e.target.result);
            };

            reader.readAsDataURL(file);
        });
    }

    // format date & time for announcement popup
    $('.offer-timer').each(function () {
        let $this = $(this);

        let date = new Date($this.data('end_date'));
        let year = parseInt(new Intl.DateTimeFormat('en', {
            year: 'numeric'
        }).format(date));
        let month = parseInt(new Intl.DateTimeFormat('en', {
            month: 'numeric'
        }).format(date));
        let day = parseInt(new Intl.DateTimeFormat('en', {
            day: '2-digit'
        }).format(date));

        let time = $this.data('end_time');
        time = time.split(':');
        let hour = parseInt(time[0]);
        let minute = parseInt(time[1]);

        $this.syotimer({
            year: year,
            month: month,
            day: day,
            hour: hour,
            minute: minute
        });
    });
})(window.jQuery);

function appearPopup($this) {
    'use strict';
    let closedPopups = [];

    if (sessionStorage.getItem('closedPopups')) {
        closedPopups = JSON.parse(sessionStorage.getItem('closedPopups'));
    }

    // if the popup is not in closedPopups Array
    if (closedPopups.indexOf($this.data('popup_id')) == -1) {
        $('#' + $this.attr('id')).show();

        let popupDelay = $this.data('popup_delay');

        setTimeout(function () {
            jQuery.magnificPopup.open({
                items: {
                    src: '#' + $this.attr('id')
                },
                type: 'inline',
                callbacks: {
                    afterClose: function () {
                        // after the popup is closed, store it in the sessionStorage & show next popup
                        closedPopups.push($this.data('popup_id'));
                        sessionStorage.setItem('closedPopups', JSON.stringify(closedPopups));

                        if ($this.next('.popup-wrapper').length > 0) {
                            appearPopup($this.next('.popup-wrapper'));
                        }
                    }
                }
            }, 0);
        }, popupDelay);
    } else {
        if ($this.next('.popup-wrapper').length > 0) {
            appearPopup($this.next('.popup-wrapper'));
        }
    }
}

// count total view of an advertisement
function adView($id) {
    'use strict';
    let url = baseURL + '/advertisement/' + $id + '/count-view';

    let data = {
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };

    $.post(url, data, function (response) {
        if ('success' in response) { } else { }
    });
}
