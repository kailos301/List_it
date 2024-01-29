<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Shipping Charge') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxForm" class="modal-form create" action="{{ route('admin.shop_management.store_charge') }}" method="post">
          @csrf
          <div class="form-group">
            <label for="">{{ __('Language') . '*' }}</label>
            <select name="language_id" class="form-control">
              <option selected disabled>{{ __('Select a Language') }}</option>
              @foreach ($langs as $lang)
                <option value="{{ $lang->id }}">{{ $lang->name }}</option>
              @endforeach
            </select>
            <p id="err_language_id" class="mt-2 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Title') . '*' }}</label>
            <input type="text" class="form-control" name="title" placeholder="Enter Title">
            <p id="err_title" class="mt-2 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Short Text') . '*' }}</label>
            <textarea class="form-control" name="short_text" rows="2" cols="80" placeholder="Enter Short Text"></textarea>
            <p id="err_short_text" class="mt-2 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            @php $currencyText = $currencyInfo->base_currency_text; @endphp

            <label for="">{{ __('Charge') . ' (' . $currencyText . ')*' }}</label>
            <input type="number" step="0.01" class="form-control ltr" name="shipping_charge" placeholder="Enter Shipping Charge">
            <p id="err_shipping_charge" class="mt-2 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Serial Number') . '*' }}</label>
            <input type="number" class="form-control ltr" name="serial_number" placeholder="Enter Serial Number">
            <p id="err_serial_number" class="mt-2 mb-0 text-danger em"></p>
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
        <button id="submitBtn" type="button" class="btn btn-primary btn-sm">
          {{ __('Save') }}
        </button>
      </div>
    </div>
  </div>
</div>
