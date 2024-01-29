<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Admin') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxEditForm" class="modal-form" action="{{ route('admin.admin_management.update_admin') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" id="in_id" name="id">

          <div class="form-group">
            <label for="">{{ __('Image') . '*' }}</label>
            <br>
            <div class="thumb-preview">
              <img src="" alt="admin image" class="in_image uploaded-img">
            </div>

            <div class="mt-3">
              <div role="button" class="btn btn-primary btn-sm upload-btn">
                {{ __('Choose Image') }}
                <input type="file" class="img-input" name="image">
              </div>
            </div>
            <p class="mt-2 mb-0 text-danger" id="editErr_image"></p>
          </div>

          <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="">{{ __('Role') . '*' }}</label>
                <select name="role_id" id="in_role_id" class="form-control">
                  <option disabled>{{ __('Select a Role') }}</option>
                  @foreach ($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                  @endforeach
                </select>
                <p id="editErr_role_id" class="mt-2 mb-0 text-danger em"></p>
              </div>
            </div>
          </div>

          <div class="row no-gutters">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ __('Username') . '*' }}</label>
                <input type="text" id="in_username" class="form-control" name="username" placeholder="Enter Username">
                <p id="editErr_username" class="mt-2 mb-0 text-danger em"></p>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ __('Email') . '*' }}</label>
                <input type="email" id="in_email" class="form-control" name="email" placeholder="Enter Email">
                <p id="editErr_email" class="mt-2 mb-0 text-danger em"></p>
              </div>
            </div>
          </div>

          <div class="row no-gutters">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ __('First Name') . '*' }}</label>
                <input type="text" id="in_first_name" class="form-control" name="first_name" placeholder="Enter First Name">
                <p id="editErr_first_name" class="mt-2 mb-0 text-danger em"></p>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ __('Last Name') . '*' }}</label>
                <input type="text" id="in_last_name" class="form-control" name="last_name" placeholder="Enter Last Name">
                <p id="editErr_last_name" class="mt-2 mb-0 text-danger em"></p>
              </div>
            </div>
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
