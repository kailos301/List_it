<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Shipping Charge') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxEditForm" class="modal-form" action="{{ route('admin.shop_management.update_charge') }}" method="post">
          @csrf
          <input type="hidden" id="in_id" name="id">

          <div class="form-group">
            <label for="">{{ __('Title') . '*' }}</label>
            <input type="text" id="in_title" class="form-control" name="title" placeholder="Enter Title">
            <p id="editErr_title" class="mt-2 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Short Text') . '*' }}</label>
            <textarea class="form-control" id="in_short_text" name="short_text" rows="2" cols="80" placeholder="Enter Short Text"></textarea>
            <p id="editErr_short_text" class="mt-2 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            @php $currencyText = $currencyInfo->base_currency_text; @endphp

            <label for="">{{ __('Charge') . ' (' . $currencyText . ')*' }}</label>
            <input type="number" step="0.01" id="in_shipping_charge" class="form-control ltr" name="shipping_charge" placeholder="Enter Shipping Charge">
            <p id="editErr_shipping_charge" class="mt-2 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Serial Number') . '*' }}</label>
            <input type="number" id="in_serial_number" class="form-control ltr" name="serial_number" placeholder="Enter Serial Number">
            <p id="editErr_serial_number" class="mt-2 mb-0 text-danger em"></p>
            <p class="text-warning mt-2 mb-0">
              <small>{{ __('The higher the serial number is, the later the shipping charge will be shown.') }}</small>
            </p>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
          {{ __('Close') }}
        </button>
        <button id="updateBtn" type="button" class="btn btn-primary btn-sm">
          {{ __('Update') }}
        </button>
      </div>
    </div>
  </div>
</div>
