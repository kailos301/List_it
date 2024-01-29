<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Advertisement') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxForm" class="modal-form" action="{{ route('admin.advertise.store_advertisement') }}"
          method="post" enctype="multipart/form-data">
          @csrf
          <div class="form-group">
            <label for="">{{ __('Advertisement Type') . '*' }}</label>
            <select name="ad_type" class="form-control ad-type">
              <option selected disabled>{{ __('Select a Type') }}</option>
              <option value="banner">{{ __('Banner') }}</option>
              <option value="adsense">{{ __('Google AdSense') }}</option>
            </select>
            <p id="err_ad_type" class="mt-2 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Advertisement Resolution') . '*' }}</label>
            <select name="resolution_type" class="form-control">
              <option selected disabled>{{ __('Select a Resolution') }}</option>
              <option value="1">{{ __('300 x 250') }}</option>
              <option value="2">{{ __('300 x 600') }}</option>
              <option value="3">{{ __('728 x 90') }}</option>
            </select>
            <p id="err_resolution_type" class="mt-2 mb-0 text-danger em"></p>
          </div>

          <div class="form-group d-none" id="image-input">
            <label for="">{{ __('Image') . '*' }}</label>
            <br>
            <div class="thumb-preview">
              <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
            </div>

            <div class="mt-3">
              <div role="button" class="btn btn-primary btn-sm upload-btn">
                {{ __('Choose Image') }}
                <input type="file" class="img-input" name="image">
              </div>
            </div>
            <p class="mt-2 mb-0 text-danger em" id="err_image"></p>
          </div>

          <div class="form-group d-none" id="url-input">
            <label for="">{{ __('Redirect URL') . '*' }}</label>
            <input type="url" class="form-control" name="url" placeholder="Enter Redirect URL">
            <p id="err_url" class="mt-2 mb-0 text-danger em"></p>
          </div>

          <div class="form-group d-none" id="slot-input">
            <label for="">{{ __('Ad Slot') . '*' }}</label>
            <input type="text" class="form-control" name="slot" placeholder="Enter Ad Slot">
            <p id="err_slot" class="mt-2 mb-0 text-danger em"></p>
            <p class="mt-2 mb-0">
              <a href="//prnt.sc/1uwa420" target="_blank" class="redirect-link">{{ __('Click Here') }}</a>
              {{ __('to see where you can find the ad slot') . '.' }}
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
