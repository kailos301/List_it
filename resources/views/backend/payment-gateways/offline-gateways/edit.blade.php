<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Gateway') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxEditForm" class="modal-form" action="{{ route('admin.payment_gateways.update_offline_gateway') }}" method="POST">
          @csrf
          <input type="hidden" id="in_id" name="id">

          <div class="row">
            <div class="col-lg-12">
              <div class="form-group">
                <label for="">{{ __('Gateway Name') . '*' }}</label>
                <input type="text" id="in_name" class="form-control" name="name" placeholder="Enter Payment Gateway Name">
                <p id="editErr_name" class="mt-2 mb-0 text-danger em"></p>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="">{{ __('Short Description') }}</label>
            <textarea id="in_short_description" class="form-control" name="short_description" rows="3" cols="80" placeholder="Enter Short Description"></textarea>
          </div>

          <div class="form-group">
            <label for="">{{ __('Instructions') }}</label>
            <textarea id="in_instructions" class="form-control summernote" name="instructions" rows="3" cols="80" placeholder="Enter Gateway Instructions" data-height="150"></textarea>
          </div>

          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label>{{ __('Attachment Status') . '*' }}</label>
                <div class="selectgroup w-100">
                  <label class="selectgroup-item">
                    <input type="radio" name="has_attachment" value="1" class="selectgroup-input">
                    <span class="selectgroup-button">{{ __('Active') }}</span>
                  </label>
                  <label class="selectgroup-item">
                    <input type="radio" name="has_attachment" value="0" class="selectgroup-input">
                    <span class="selectgroup-button">{{ __('Deactive') }}</span>
                  </label>
                </div>
                <p id="editErr_has_attachment" class="mb-0 text-danger em"></p>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ __('Serial Number') . '*' }}</label>
                <input type="number" id="in_serial_number" class="form-control ltr" name="serial_number" placeholder="Enter Serial Number">
                <p id="editErr_serial_number" class="mt-2 mb-0 text-danger em"></p>
                <p class="text-warning mt-2 mb-0">
                  <small>{{ __('The higher the serial number is, the later the gateway will be shown.') }}</small>
                </p>
              </div>
            </div>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          {{ __('Close') }}
        </button>
        <button id="updateBtn" type="button" class="btn btn-primary">
          {{ __('Update') }}
        </button>
      </div>
    </div>
  </div>
</div>
