<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Slider') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxEditForm" class="modal-form"
          action="{{ route('admin.home_page.hero_section.slider_version.update') }}" method="post"
          enctype="multipart/form-data">
          @csrf
          <input type="hidden" id="in_id" name="id">

          <div class="form-group">
            <label for="">{{ __('Background Image') . '*' }}</label>
            <br>
            <div class="thumb-preview">
              <img src="" alt="..." class="uploaded-background-img in_image">
            </div>

            <div class="mt-3">
              <div role="button" class="btn btn-primary btn-sm upload-btn">
                {{ __('Choose Image') }}
                <input type="file" class="background-img-input" name="background_image">
              </div>
            </div>
            <p id="editErr_background_image" class="mt-2 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Title') }}</label>
            <input type="text" class="form-control" name="title" placeholder="Enter Title" id="in_title">
          </div>
          @if ($settings->theme_version != 1)
            <div class="form-group">
              <label for="">{{ __('Text') }}</label>
              <textarea class="form-control" name="text" rows="5" placeholder="Enter Text" id="in_text"></textarea>
            </div>
          @endif
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
