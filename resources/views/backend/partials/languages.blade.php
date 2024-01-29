@if (!empty($langs))
  <select name="language" class="form-control" onchange="window.location='{{ url()->current() . '?language=' }}' + this.value">
    <option selected disabled>{{ __('Select a Language') }}</option>
    @foreach ($langs as $lang)
      <option value="{{ $lang->code }}" {{ $lang->code == request()->input('language') ? 'selected' : '' }}>
        {{ $lang->name }}
      </option>
    @endforeach
  </select>
@endif
