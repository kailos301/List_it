<!-- Modal -->
<div class="modal fade" id="addNextPackage" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Next Package') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="addNextPackageForm" action="{{ route('vendor.nextPackage.add') }}" method="POST">
          @csrf
          <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">
          <div class="form-group">
            <label for="">{{ __('Package') }} **</label>
            <select name="package_id" id="" class="form-control" required>
              <option value="" selected disabled>{{ __('Select a Package') }}</option>
              @foreach ($packages as $package)
                <option value="{{ $package->id }}"
                  {{ !empty($nextPackage) && $nextPackage->id == $package->id ? 'selected' : '' }}>{{ $package->title }}
                  ({{ $package->term }})
                </option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="">{{ __('Payment Method') }}</label>
            <select name="payment_method" class="form-control">
              <option value="" selected disabled>{{ __('Select a Payment Method') }}</option>
              @foreach ($gateways as $gateway)
                <option value="{{ $gateway->name }}">{{ $gateway->name }}</option>
              @endforeach
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
        <button type="submit" form="addNextPackageForm" class="btn btn-primary">{{ __('Add') }}</button>
      </div>
    </div>
  </div>
</div>
