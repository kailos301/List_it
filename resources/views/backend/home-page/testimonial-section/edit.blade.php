<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Testimonial') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxEditForm" class="modal-form" action="{{ route('admin.home_page.update_testimonial') }}"
          method="post" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="id" id="in_id">

          <div class="form-group">
            <label for="">{{ __('Client Image') . '*' }}</label>
            <br>
            <div class="thumb-preview">
              <img src="" alt="..." class="uploaded-img in_image">
            </div>

            <div class="mt-3">
              <div role="button" class="btn btn-primary btn-sm upload-btn">
                {{ __('Choose Image') }}
                <input type="file" class="img-input" name="image">
              </div>
            </div>
            <p id="editErr_image" class="mt-2 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Name') . '*' }}</label>
            <input type="text" class="form-control" name="name" placeholder="Enter Client Name" id="in_name">
            <p id="editErr_name" class="mt-2 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Occupation') . '*' }}</label>
            <input type="text" class="form-control" name="occupation" placeholder="Enter Client Occupation"
              id="in_occupation">
            <p id="editErr_occupation" class="mt-2 mb-0 text-danger em"></p>
          </div>
          <div class="form-group">
            <label for="">{{ __('Rating') . '*' }}</label>
            <input type="text" class="form-control" name="rating" placeholder="Enter Client Rating" id="in_rating">
            <p id="editErr_rating" class="mt-2 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Comment') . '*' }}</label>
            <textarea class="form-control" name="comment" placeholder="Enter Client Comment" rows="4" id="in_comment"></textarea>
            <p id="editErr_comment" class="mt-2 mb-0 text-danger em"></p>
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
