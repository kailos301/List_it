<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Update Language') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxEditForm" action="{{ route('admin.language_management.update') }}" method="POST">
          @csrf
          <input type="hidden" id="in_id" name="id">

          <div class="form-group">
            <label for="">{{ __('Name') . '*' }}</label>
            <input id="in_name" type="text" class="form-control" name="name" placeholder="Enter Language Name">
            <p id="editErr_name" class="mt-2 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Code') . '*' }}</label>
            <input id="in_code" type="text" class="form-control" name="code" placeholder="Enter Language Code">
            <p id="editErr_code" class="mt-2 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Direction') . '*' }}</label>
            <select id="in_direction" name="direction" class="form-control">
              <option disabled>{{ __('Select a Direction') }}</option>
              <option value="0">{{ __('LTR (Left To Right)') }}</option>
              <option value="1">{{ __('RTL (Right To Left)') }}</option>
            </select>
            <p id="editErr_direction" class="mt-2 mb-0 text-danger em"></p>
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
